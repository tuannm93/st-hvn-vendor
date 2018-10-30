<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\MUserRepositoryInterface;
use App\Http\Requests\RegisterUserFormRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * @var MUserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var array
     */
    protected $sysAdmin = ["system"];

    /**
     * UserController constructor.
     *
     * @param MUserRepositoryInterface $mUserRepository
     * @param UserService              $userService
     */
    public function __construct(MUserRepositoryInterface $mUserRepository, UserService $userService)
    {
        parent::__construct();
        $this->userRepository = $mUserRepository;
        $this->userService = $userService;
        $this->pageNumber = 100;
    }

    /**
     * @param RegisterUserFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(RegisterUserFormRequest $request)
    {
        if ($this->userService->createUser($request->all())) {
            return redirect()->route('user.create')->with(['message' => trans('user.created'), 'check' => trans('user.insert_done')]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $authList = (Auth::user()->auth === 'system')  ? getDivList('datacustom.auth_list', 'auth_list_config') : getDivList('datacustom.auth_list_aff', 'auth_list_config');
        $authList = array_prepend($authList, trans("user_index.unselect"), "");
        return view('user.detail', compact('authList'));
    }

    /**
     * Show form search user
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $authList = getDivList('datacustom.auth_list', 'auth_list_config');
        $authList = array_prepend($authList, trans("user_index.unselect"), "");

        $username = Session::has('user_name') ? Session::get('user_name') : null;
        $corpName = Session::has('corp_name') ? Session::get('corp_name') : null;
        $auth     = Session::has('auth') ? Session::get('auth') : null;
        if(Session::has('isBack')){
            $isBack = true;
            Session::forget('isBack');
        }else{
            $isBack = false;
            $username = null;
            $corpName = null;
            $auth     = null;
        }
        return view("user.index", ["authList" => $authList,
            "auth"     => $auth,
            "username" => $username,
            "corpName" => $corpName,
            "isBack"   => $isBack]);
    }

    /**
     * Show list users
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $auth = null;
        $username = null;
        $corpName = null;

        if ($request->isMethod('post')) {
            $auth = $request->input("auth", null);
            $username = chgSearchValue($request->input("user_name", null));
            $corpName = chgSearchValue($request->input("corp_name", null));
            $request->session()->put('auth', $auth);
            $request->session()->put('user_name', $username);
            $request->session()->put('corp_name', $corpName);
        } else {
            $auth = $request->session()->get('auth', null);
            $username = $request->session()->get('user_name', null);
            $corpName = $request->session()->get('corp_name', null);
        }

        $authList = getDivList('datacustom.auth_list', 'auth_list_config');
        $authList = array_prepend($authList, trans("user_index.unselect"), "");

        if ($request->input("submit") == "export") {
            $results = $this->userRepository->getUserForSearch(-1, $auth, $username, $corpName);

            return $this->userService->exportCSV($this->getUser(), $results, $authList);
        } else {
            $results = $this->userRepository->getUserForSearch($this->pageNumber, $auth, $username, $corpName);
        }

        return response()->view("user.components._table", ["authList" => $authList, "checkSysAdmin" => in_array($this->getUser()->auth, $this->sysAdmin), "results" => $results]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $authList = (Auth::user()->auth === 'system')  ? getDivList('datacustom.auth_list', 'auth_list_config') : getDivList('datacustom.auth_list_aff', 'auth_list_config');
        $authList = array_prepend($authList, trans("user_index.unselect"), "");
        $dataUser = $this->userService->getUserById($id);
        $dataMcorp = $this->userService->getMCorpById($dataUser['affiliation_id']);
        return view('user.update', compact('authList', 'id', 'dataUser', 'dataMcorp'));
    }

    /**
     * @param RegisterUserFormRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(RegisterUserFormRequest $request, $id)
    {
        if ($this->userService->updateUser($id, $request->all())) {
            return redirect()->route('user.edit', $id)->with(['message' => trans('user.updated')]);
        }
    }
}
