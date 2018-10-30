<?php

namespace App\Repositories\Eloquent;

use App\Models\License;
use App\Helpers\Util;
use App\Repositories\AgreementAdminLicenseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgreementAdminLicenseRepository extends SingleKeyModelRepository implements AgreementAdminLicenseRepositoryInterface
{

    /**
     * @var License
     */
    protected $model;

    /**
     * AgreementAdminLicenseRepository constructor.
     *
     * @param License $license
     */
    public function __construct(License $license)
    {
        $this->model = $license;
    }

    /**
     * @return object
     */
    public function getAllLicense()
    {
        $query = $this->model
            ->select(
                'id',
                'name',
                DB::raw("(CASE certificate_required_flag WHEN true THEN '" . License::HAVE_TO . "' ELSE '' END) AS certificate_required_flag_converted")
            )
            ->groupBy(
                [
                'id'
                ]
            );
        return $query;
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getLicenseById($id)
    {
        return $this->model
            ->select(
                'license.id',
                'license.name',
                DB::raw("(CASE certificate_required_flag WHEN true THEN '" . License::HAVE_TO . "' ELSE '' END) AS certificate_required_flag_converted"),
                DB::raw('to_char(license.update_date, \'YYYY/MM/DD HH24:MI\') as update_date'),
                'm_users.user_name'
            )
            ->where('license.id', '=', $id)
            ->join('m_users', DB::raw('license.update_user_id::int'), '=', 'm_users.id')
            ->first();
    }

    /**
     * @param Request $request
     */
    public function addLicense(Request $request)
    {
        $license = [
            'name' => $request->input('name'),
            'certificate_required_flag' => $request->input('certificate_required_flag'),
            'create_user_id' => Auth::user()->id,
            'create_date' => date('Y-m-d H:i:s'),
            'update_user_id' => Auth::user()->id,
            'update_date' => date('Y-m-d H:i:s'),
            'version_no' => 1
        ];
        $this->model->insert($license);
    }

    /**
     * @param integer $id
     * @throws \Exception
     */
    public function deleteLicenseById($id)
    {
        $this->model->where('id', '=', $id)->delete();
    }

    /**
     * @param Request $request
     */
    public function updateLicense(Request $request)
    {
        $originLicense = $this->model->where('id', $request->input('id'))->first();
        $certificateRequiredFlag = $originLicense->certificate_required_flag ? 'true' : 'false';

        if (strcmp($request->input('name'), $originLicense->name) != 0 ||
            strcmp($request->input('certificate_required_flag'), $certificateRequiredFlag) != 0) {
            // data were changed
            $newLicense = [
                'name' => $request->input('name'),
                'certificate_required_flag' => $request->input('certificate_required_flag'),
                'update_user_id' => Auth::user()->id,
                'update_date' => date('Y-m-d H:i:s'),
                'version_no' => $originLicense->version_no + 1
            ];
            $this->model->where('id', $request->input('id'))->update($newLicense);
        }
    }
}
