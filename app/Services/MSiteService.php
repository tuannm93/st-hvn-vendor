<?php

namespace App\Services;

use App\Repositories\MSiteRepositoryInterface;

class MSiteService
{
    /**
     * @var MSiteRepositoryInterface
     */
    public $siteRepo;

    /**
     * MSiteService constructor.
     *
     * @param MSiteRepositoryInterface $siteRepo
     */
    public function __construct(
        MSiteRepositoryInterface $siteRepo
    ) {
        $this->siteRepo = $siteRepo;
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function getSiteUrl($id)
    {
        $site = $this->siteRepo->find($id);
        if ($site) {
            return $site->site_url;
        } else {
            return '';
        }
    }
}
