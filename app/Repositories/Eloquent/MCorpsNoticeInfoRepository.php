<?php

namespace App\Repositories\Eloquent;

use App\Repositories\MCorpsNoticeInfoRepositoryInterface;
use App\Models\MCorpsNoticeInfo;

class MCorpsNoticeInfoRepository extends SingleKeyModelRepository implements MCorpsNoticeInfoRepositoryInterface
{
    /**
     * @var MCorpsNoticeInfo
     */
    protected $model;

    /**
     * MCorpsNoticeInfoRepository constructor.
     *
     * @param MCorpsNoticeInfo $model
     */
    public function __construct(MCorpsNoticeInfo $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MCorpsNoticeInfo|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCorpsNoticeInfo();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }

    /**
     * @param $user
     * @param $noticeId
     * @param $corp
     * @return \Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function markReadNotice($user, $noticeId, $corp)
    {
        if ($user->auth != 'affiliation' || empty($corp)) {
            return null;
        }

        return $this->model->firstOrCreate(
            [
            'm_corp_id'         => $corp->id,
            'notice_info_id'    => $noticeId,
            'modified_user_id'  => $user->user_id,
            'created_user_id'   => $user->user_id
            ]
        );
    }

    /**
     * @param object $noticeInfo
     * @param object $user
     * @param string $answerValue
     * @return mixed|void
     */
    public function answerNotice($noticeInfo, $user, $answerValue)
    {
        if (!empty($noticeInfo)) {
            $noticeInfo->answer_value = $answerValue;
            $noticeInfo->answer_user_id = $user->user_id;
            $noticeInfo->answer_date = date('Y-m-d H:i:s');
            $noticeInfo->modified_user_id = $user->user_id;
            $noticeInfo->save();
        }
    }

    /**
     * @param integer $noticeId
     * @return bool|mixed
     */
    public function isNoticeAnswered($noticeId)
    {
        return $this->model->where('notice_info_id', $noticeId)
            ->whereNotNull('answer_value')
            ->exists();
    }

    /**
     * @param integer $noticeId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findAnswerListByNoticeInfoId($noticeId)
    {
        return $this->model->leftJoin('m_corps', 'm_corps.id', '=', 'm_corps_notice_infos.m_corp_id')
            ->where('notice_info_id', $noticeId)
            ->whereNotNull('answer_value')
            ->get();
    }

    /**
     * @param integer $noticeId
     * @return array|mixed
     */
    public function getListAnswerCSV($noticeId)
    {
        return $this->model
            ->leftJoin('m_corps', 'm_corps.id', '=', 'm_corps_notice_infos.m_corp_id')
            ->leftJoin('m_users', 'm_users.user_id', '=', 'm_corps_notice_infos.answer_user_id')
            ->where('m_corps_notice_infos.notice_info_id', $noticeId)
            ->whereNotNull('m_corps_notice_infos.answer_value')
            ->select(
                'm_corps.id AS id',
                'm_corps.official_corp_name AS official_corp_name',
                'm_corps.corp_name_kana AS corp_name_kana',
                'm_corps_notice_infos.answer_value AS answer_value',
                'm_corps_notice_infos.answer_date AS answer_date',
                'm_users.user_name AS user_name'
            )
            ->get()->toArray();
    }
}
