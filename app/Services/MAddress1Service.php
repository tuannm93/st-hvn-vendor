<?php


namespace App\Services;

use App\Repositories\MAddress1RepositoryInterface;

class MAddress1Service
{
    /**
     * @var MAddress1RepositoryInterface
     */
    protected $mAddress1Repository;

    /**
     * MAddress1Service constructor.
     *
     * @param MAddress1RepositoryInterface $mAddress1Repository
     */
    public function __construct(MAddress1RepositoryInterface $mAddress1Repository)
    {
        $this->mAddress1Repository = $mAddress1Repository;
    }


    /**
     * @param $corpId
     * @return array
     */
    public function getListArea($corpId)
    {
        $result = [];
        $addressAreaList = $this->mAddress1Repository->findAreaDataSetByCorpIdAndPrefecturalCode($corpId);
        foreach ($addressAreaList as $address) {
            $addressConvert = json_decode(json_encode($address), true);
            if ($addressConvert['register_post_count'] == null || $addressConvert['register_post_count'] === 0) {
                $addressConvert['status'] = 1;
            } elseif ($addressConvert['register_post_count'] !== 0
                && $addressConvert['register_post_count'] === $addressConvert['data_post_count']
            ) {
                $addressConvert['status'] = 3;
            } else {
                $addressConvert['status'] = 2;
            }
            array_push($result, $addressConvert);
        }
        return $result;
    }
}
