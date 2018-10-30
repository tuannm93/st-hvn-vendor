<?php

namespace App\Http\Controllers\Genre;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MGenresRepositoryInterface;

use App\Services\UserService;

class GenreController extends Controller
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var MGenresRepositoryInterface
     */
    public $mGenreRepository;

    /**
     * construct function
     * @param Request                    $request
     * @param MGenresRepositoryInterface $mGenreRepository
     */
    public function __construct(Request $request, MGenresRepositoryInterface $mGenreRepository)
    {
        parent::__construct();
        $this->request = $request;
        $this->mGenreRepository = $mGenreRepository;
    }

    /**
     * get info from m_genres
     * @return view
     */
    public function index()
    {
        $acaAccount = false;
        if (UserService::checkRole('accounting_admin')) {
            $acaAccount = true;
        }
        $allGenre = $this->mGenreRepository->getAll();

        return view(
            'genre.index',
            [
            "allGenre" => $allGenre,
            'acaAccount' => $acaAccount
            ]
        );
    }

    /**
     * update table m_genres
     * @return null
     */
    public function regist()
    {
        $data = $this->request->all();
        try {
            $resultsFlg = $this->mGenreRepository->editGenre($data);
            if ($resultsFlg) {
                $this->request->session()->flash('Update', trans('genre.update'));
            } else {
                $this->request->session()->flash('InputError', trans('genre.input_error'));
            }
        } catch (Exception $e) {
            $this->request->session()->flash('InputError', trans('genre.input_error'));
        }
        return back();
    }
}
