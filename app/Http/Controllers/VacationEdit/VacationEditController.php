<?php

namespace App\Http\Controllers\VacationEdit;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MItemRepositoryInterface;
use App\Services\VacationEditService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VacationEditController extends Controller
{
    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepository;

    /**
     * @var VacationEditService
     */
    protected $vacationEditService;

    const REGEX_MATH = "/^(([1-9])|(0[1-9])|1[0-2])\/((0[1-9]|[1-9])|[12][0-9]|3[01])$/";

    /**
     * VacationEditController constructor.
     *
     * @param MItemRepositoryInterface $mItemRepository
     * @param VacationEditService      $vacationEditService
     */
    public function __construct(MItemRepositoryInterface $mItemRepository, VacationEditService $vacationEditService)
    {
        parent::__construct();
        $this->mItemRepository = $mItemRepository;
        $this->vacationEditService = $vacationEditService;
    }

    /**
     * Show long holiday
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $results = $this->mItemRepository->getByLongHoliday();

        return view(
            "vacation_edit.index",
            [
            "results" => $results->keyBy('item_id'),
            ]
        );
    }

    /**
     * Update long holiday
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  \Illuminate\Http\Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $data = $request->get("mItem");
        foreach ($data as $key => $value) {
            if (empty($value["item_name"]) || is_null($value["item_name"])) {
                unset($data[$key]);
            } else {
                if (!preg_match(self::REGEX_MATH, $value["item_name"])) {
                    return redirect()->back()->with(['error' => trans('vacation_edit.valid')])->withInput();
                } else {
                    $data[$key]["item_id"] = $key;
                    $data[$key]["item_category"] = MItemRepository::LONG_HOLIDAYS;
                    $data[$key]["sort_order"] = $key;
                    $data[$key]["enabled_start"] = date('Y/m/d');
                    $data[$key]["created_user_id"] = $this->getUser()->user_id;
                    $data[$key]["modified_user_id"] = $this->getUser()->user_id;
                    $data[$key]["created"] = Carbon::now();
                    $data[$key]["modified"] = Carbon::now();
                }
            }
        }

        $result = $this->vacationEditService->update($data);
        if ($result) {
            return redirect()->back()->with(['success' => trans('vacation_edit.update_success')]);
        } else {
            return redirect()->back()->with(['error' => trans('vacation_edit.update_fail')]);
        }
    }
}
