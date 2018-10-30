<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandInquiryAns;
use App\Repositories\DemandInquiryAnsRepositoryInterface;

class DemandInquiryAnsRepository extends SingleKeyModelRepository implements DemandInquiryAnsRepositoryInterface
{
    /**
     * @var DemandInquiryAns
     */
    protected $model;

    /**
     * DemandInquiryAnsRepository constructor.
     *
     * @param DemandInquiryAns $model
     */
    public function __construct(DemandInquiryAns $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|DemandInquiryAns|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new DemandInquiryAns();
    }

    /**
     * @param integer $demandId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getDemandInquiryWithMInquiryByDemand($demandId)
    {
        return $this->model->with(["mInquiry"])->where("demand_id", $demandId)->orderBy("id", "asc")->get();
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findById($id)
    {
        $fields = $this->getAllTableFieldsByAlias('demand_inquiry_answers', 'DemandInquiryAnswer');

        $query = $this->model
            ->from('demand_inquiry_answers AS DemandInquiryAnswer')
            ->join(
                'm_inquiries AS MInquiry',
                function ($join) {
                            $join->on('MInquiry.id', 'DemandInquiryAnswer.inquiry_id');
                }
            )
                    ->where('DemandInquiryAnswer.demand_id', $id)
                    ->orderBy('DemandInquiryAnswer.id', 'ASC')
                    ->select($fields);

        $result = $query->first();

        return $result;
    }

    /**
     * @param integer $demandId
     * @param integer $inquiryId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getDemandAnswerByDemandIdAndInquiryId($demandId, $inquiryId)
    {
        return $this->model->where('demand_id', $demandId)->where('inquiry_id', $inquiryId)->first();
    }

    /**
     * @param array $saveData
     */
    public function multipleUpdate($saveData)
    {
        foreach ($saveData as $value) {
            $this->model->where('id', $value['id'])->update($value);
        }
    }
}
