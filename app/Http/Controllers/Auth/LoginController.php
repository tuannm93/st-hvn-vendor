<?php
namespace App\Http\Controllers\Auth;

use App\Repositories\MUserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\LoginService;
use App\Services\UserService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Session;
use Auth;
use Jenssegers\Agent\Agent;
use App\Repositories\DeviceInfoRepositoryInterface;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * @var LoginService
     */
    public $loginService;

    /**
     * @var MUserRepositoryInterface
     */
    public $userRepository;

    /**
     * @var DeviceInfoRepositoryInterface
     */
    public $deviceRepo;

    const LOGOUT_FOR_MOBILE = 'logout_for_mobile';
    /**
     * Create a new controller instance.
     *
     * @param LoginService             $loginService
     * @param MUserRepositoryInterface $userRepository
     */
    public function __construct(LoginService $loginService, MUserRepositoryInterface $userRepository, DeviceInfoRepositoryInterface $deviceRepo)
    {
        $this->loginService = $loginService;
        $this->userRepository = $userRepository;
        $this->deviceRepo = $deviceRepo;
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function authenticated(Request $request)
    {
        $dataRequest = $request->all();
        if (!$this->loginService->checkGuideline($dataRequest)) {
            $this->guard()->logout();
            return redirect('/login')->withErrors(['GuideLineError' => trans('auth.guide_line_error')]);
        }
        if (!$this->loginService->checkMcorpDelflg()) {
            $this->guard()->logout();
            return redirect('/login')->withErrors(['GuideLineError' => trans('auth.guide_line_error')]);
        }
        $data['last_login_date'] = Carbon::now();
        $data['modified'] = Carbon::now();
        $this->userRepository->updateLastLogin($request->user_id, $data);

        if (UserService::checkRole('affiliation')) {
            $re = session()->pull('url.intended', '/aution');
            \Log::debug($re);
            if ($re == url('/')) {
                return redirect('auction');
            }
            if(!$request->isMethod('get')){
                return redirect('/');
            }
            return redirect()->to($re);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $agent = new Agent();
        if($agent->isAndroidOS()){
            $this->deviceRepo->deleteDeviceByUserId(auth()->user()->user_id, 'Android');
        }
        if($agent->isiOS()){
            $this->deviceRepo->deleteDeviceByUserId(auth()->user()->user_id, 'iOS');
        }
        $this->guard()->logout();
        $request->session()->invalidate();
        Session::put(LoginController::LOGOUT_FOR_MOBILE, 'log_out');
        return redirect('/');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'user_id';
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function handleLogin(Request $request)
    {
        $data = $request->all();
        if (isset($data['user_id']) && isset($data['password'])) {
            // m_usersテーブルの検索
            $user = $this->loginService->checkUserLogin($data);
            if ($user) {
                if ($user->password==$data['password']){
                    Auth::loginUsingId($user->id, true);
                    $this->userRepository->updateLastLogin($user->user_id, ['last_login_date' => Carbon::now()]);

                    $telNo = isset($data["tel_no"])?$data["tel_no"]:"";
                    $dialinNo = isset($data["dialin_no"])?$data["dialin_no"]:"";
                    return redirect('/demand_list?cti=1&customer_tel='.$telNo.'&site_tel='.$dialinNo);
                } else {
                    return redirect('/login')->withErrors(['GuideLineError' => trans('auth.cti_error').'1']);
                }
            } else {
                return redirect('/login')->withErrors(['GuideLineError' => trans('auth.cti_error').'2']);
            }
        }
        return $this->showLoginForm();
    }
}
