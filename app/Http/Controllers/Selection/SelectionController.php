<?php

namespace App\Http\Controllers\Selection;

use App\Http\Controllers\Controller;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\SelectionGenrePrefectureRepositoryInterface;
use App\Repositories\SelectionGenreRepositoryInterface;
use App\Services\SelectionService;
use Illuminate\Http\Request;

class SelectionController extends Controller
{
    /**
     * @var SelectionService
     */
    protected $selectionService;
    /**
     * @var SelectionGenreRepositoryInterface
     */
    protected $selectGenreRepository;
    /**
     * @var SelectionGenrePrefectureRepositoryInterface
     */
    protected $selectGenrePreRepository;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenreRepository;

    /**
     * SelectionController constructor.
     *
     * @param SelectionService $selectionService
     * @param SelectionGenreRepositoryInterface $selectGenreRepository
     * @param SelectionGenrePrefectureRepositoryInterface $selectGenrePreRepository
     * @param MGenresRepositoryInterface $mGenreRepository
     */
    public function __construct(
        SelectionService $selectionService,
        SelectionGenreRepositoryInterface $selectGenreRepository,
        SelectionGenrePrefectureRepositoryInterface $selectGenrePreRepository,
        MGenresRepositoryInterface $mGenreRepository
    ) {
        parent::__construct();
        $this->selectionService = $selectionService;
        $this->selectGenreRepository = $selectGenreRepository;
        $this->mGenreRepository = $mGenreRepository;
        $this->selectGenrePreRepository = $selectGenrePreRepository;
    }

    /**
     * Show list selection
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $hasRoleUser = true;

        if (auth()->user()->auth == 'accounting_admin') {
            $hasRoleUser = false;
        }

        $genres = $this->selectionService->getSelectionGenre();
        $selectionType = getDivList('datacustom.selection_type', 'selection');

        return view(
            'selection.index',
            ['genres' => $genres, 'selectionType' => $selectionType, 'hasRoleUser' => $hasRoleUser]
        );
    }

    /**
     * Update selection
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(Request $request)
    {
        $allData = $request->all();
        $save = $this->selectionService->saveAllSelectGenre($allData['data']);
        if ($save) {
            return redirect()->route('selection.index')->with(['success' => __('common.register_success')]);
        } else {
            return redirect()->back()->with(['error' => __('common.register_fail')]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function prefecture($id)
    {
        $genreList = $this->selectGenrePreRepository->getSelectGenrePrefecture($id);
        $genre = $this->mGenreRepository->find($id);
        $selectGenre = $this->selectGenreRepository->findBaseOnGenreId($id, 'select_type');
        $defaultSelectionType = !empty($selectGenre) ? $selectGenre : 0;
        $prefectures = getDivList('rits.prefecture_div', 'rits_config');
        unset($prefectures[99]);
        $selectionType = getDivList('datacustom.selection_type', 'selection');
        return view('selection.prefecture', [
            'prefectures' => $prefectures,
            'selectionType' => $selectionType,
            'genreList' => $genreList,
            'genre' => $genre,
            'defaultSelectionType' => $defaultSelectionType
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function prefecturePost(Request $request, $id)
    {
        $allData = $request->all();
        $save = $this->selectionService->saveAllSelectGenrePrefecture($id, $allData['data']);
        if ($save) {
            return redirect()->route('selection.prefecture', $id)->with(['success' => __('common.register_success')]);
        } else {
            return redirect()->back()->with(['error' => __('common.register_fail')]);
        }
    }
}
