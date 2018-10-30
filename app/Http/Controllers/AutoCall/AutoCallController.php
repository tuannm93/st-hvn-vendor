<?php

namespace App\Http\Controllers\AutoCall;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoCallSettingFormRequest;
use App\Repositories\AutoCallRepositoryInterface;

use Illuminate\Support\Facades\Lang;

class AutoCallController extends Controller
{
    /**
     * @var AutoCallRepositoryInterface
     */
    protected $autoCallRepository;

    /**
     * AutoCallController constructor.
     *
     * @param AutoCallRepositoryInterface $autoCallRepository
     */
    public function __construct(AutoCallRepositoryInterface $autoCallRepository)
    {
        parent::__construct();
        $this->autoCallRepository = $autoCallRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $item = $this->autoCallRepository->getItem();

        return view('autocall.index', compact('item'));
    }

    /**
     * @param \App\Http\Requests\AutoCallSettingFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AutoCallSettingFormRequest $request)
    {
        $data = [];
        if ($request->isMethod('post')) {
            if ($request->has('id')) {
                $data['id'] = $request->input('id');
            }
            $data['asap'] = $request->input('asap');
            $data['immediately'] = $request->input('immediately');
            $data['normal'] = $request->input('normal');

            $this->autoCallRepository->save($data);
            $request->session()->flash('success', Lang::get('autocall.message_successfully'));
        }

        return redirect()->route('autocall.index');
    }
}
