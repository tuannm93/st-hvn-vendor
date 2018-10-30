<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AgreementAttachedFileRepositoryInterface;
use App\Models\AgreementAttachedFile;

class AgreementAttachedFileRepository extends SingleKeyModelRepository implements AgreementAttachedFileRepositoryInterface
{
    /**
     * @var AgreementAttachedFile
     */
    protected $model;

    /**
     * AgreementAttachedFileRepository constructor.
     *
     * @param AgreementAttachedFile $model
     */
    public function __construct(AgreementAttachedFile $model)
    {
        $this->model = $model;
    }

    /**
     * @return AgreementAttachedFile|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AgreementAttachedFile();
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
     * get all agreement attached file by corp id and kind
     *
     * @param  integer $corpId
     * @param  string  $kind
     * @return object agreement
     */
    public function getAllAgreementAttachedFileByCorpIdAndKind($corpId, $kind)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('kind', $kind)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * find by corp id and id
     *
     * @param  integer $corpId
     * @param  integer $fileId
     * @return object
     */
    public function findByCorpIdAndId($corpId, $fileId)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('id', $fileId)
            ->first();
    }

    /**
     * find by id
     *
     * @param  integer $fileId
     * @return object
     */
    public function findById($fileId)
    {
        return $this->model
            ->where('id', $fileId)
            ->first();
    }

    /**
     * @param integer $licenseId
     * @return array
     */
    public function findByLicenseId($licenseId)
    {
        return $this->model
            ->where('license_id', $licenseId)
            ->get();
    }
}
