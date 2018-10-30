<?php


namespace App\Services;

use App\Repositories\MAddress1RepositoryInterface;
use App\Repositories\MPostRepositoryInterface;

class MPostService
{
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepository;
    /**
     * @var MAddress1RepositoryInterface
     */
    protected $mAddress1Repository;

    /**
     * MPostService constructor.
     *
     * @param MPostRepositoryInterface     $mPostRepository
     * @param MAddress1RepositoryInterface $mAddress1Repository
     */
    public function __construct(
        MPostRepositoryInterface $mPostRepository,
        MAddress1RepositoryInterface $mAddress1Repository
    ) {
        $this->mPostRepository = $mPostRepository;
        $this->mAddress1Repository = $mAddress1Repository;
    }

    /**
     * @param $zipCode
     * @return array
     */
    public function getSearchZipCode($zipCode)
    {
        $mPost = $this->mPostRepository->findByPostCd($zipCode);
        $address = [];
        if (!is_null($mPost)) {
            $address['address2'] = $mPost->address2;
            $address['address3'] = $mPost->address3;
            $mAddress1 = $this->mAddress1Repository->findByAddressName($mPost->address1);
            if (is_null($mAddress1)) {
                $address['address1'] = "";
            } else {
                $address['address1_cd'] = $mAddress1->address1_cd;
                $address['address1'] = $mAddress1->address1;
            }
        }
        return $address;
    }
}
