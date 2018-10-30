<?php

namespace App\Services;

use App\Repositories\MUserRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Models\MUser;
use Auth;

class UserService
{
    /**
     * @var MUserRepositoryInterface
     */
    protected $userRepo;

    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepo;

    /**
     * UserService constructor.
     *
     * @param MUserRepositoryInterface $userRepo
     * @param MCorpRepositoryInterface $mCorpRepo
     */
    public function __construct(
        MUserRepositoryInterface $userRepo,
        MCorpRepositoryInterface $mCorpRepo
    ) {
        $this->userRepo = $userRepo;
        $this->mCorpRepo = $mCorpRepo;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function createUser($request)
    {

        $data = [
            'user_id' => $request['user_id'],
            'user_name' => $request['user_name'],
            'password' => hash('sha256', $request['password'], false),
            'auth' => $request['auth'],
            'modified_user_id' => Auth::user()->user_id,
            'modified' => date('Y-m-d H:i'),
            'created_user_id' => Auth::user()->user_id,
            'created' => date('Y-m-d H:i'),
            'password_modify_date' => date('Y-m-d H:i')
        ];

        if (!empty($request['official_corp_name']) && $request['auth'] == 'affiliation') {
            $mCorpData = $this->mCorpRepo->findByName($request['official_corp_name']);
            $data += ['affiliation_id' => (int)$mCorpData['id']];
        }

        return $this->userRepo->saveUser($data);
    }

    /**
     * Export CSV
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  $user
     * @param  $data
     * @param  $authList
     * @return mixed
     */
    public function exportCSV($user, $data, $authList)
    {
        $fileName = trans('user_search.file_name').$user->user_id.".csv";
        $fieldList = MUser::csvFormat();
        $csvData = [];
        foreach ($data as $value) {
            $csvData[] = [
                "user_name" => $value->user_name,
                "corp_id" => $value->m_corps_id,
                "official_corp_name" => $value->official_corp_name,
                "auth" => $authList[$value->auth],
                "last_login_date" => $value->last_login_date,
            ];
        }

        return $this->export($fileName, $fieldList, $csvData);
    }

    /**
     * @param $fileName
     * @param $columns
     * @param $rowList
     * @param string   $type
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function export($fileName, $columns, $rowList, $type = "text/csv")
    {
        try {
            $headers = [
                'Content-type' => $type,
                'Content-Disposition' => 'attachment; filename='.$fileName,
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function () use ($columns, $rowList) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, $columns);

                foreach ($rowList as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            abort('500');
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return $this->userRepo->getUserById($id);
    }

    /**
     * @param $userId
     * @param $data
     * @return mixed
     */
    public function updateUser($userId, $data)
    {
        unset($data['_token']);
        unset($data['password_confirm']);
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = hash('sha256', $data['password'], false);
            $data['password_modify_date'] = date('Y-m-d H:i');
        }

        if (empty($data['affiliation_id'])) {
            unset($data['affiliation_id']);
        }

        if (!empty($data['official_corp_name'])  && $data['auth'] == 'affiliation') {
            $mCorpData = $this->mCorpRepo->findByName($data['official_corp_name']);
            $data['affiliation_id'] = (int)$mCorpData['id'];
        }

        unset($data['official_corp_name']);
        $data['modified_user_id'] = Auth::user()->user_id;
        $data['modified'] = date('Y-m-d H:i');

        return $this->userRepo->updateUser($userId, $data);
    }

    /**
     * @param $corpId
     * @return mixed
     */
    public function getMCorpById($corpId)
    {
        return $this->mCorpRepo->getFirstById($corpId, true);
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public static function checkRole($roleName)
    {
        return Auth::user()->auth == $roleName ? true : false;
    }
}
