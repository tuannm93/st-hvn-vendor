<?php

namespace App\Services;

use App\Repositories\NoticeInfoRepositoryInterface;

class NoticeInfoService
{
    /**
     * @var NoticeInfoRepositoryInterface
     */
    protected $noticeInfoRepository;
    /**
     * NoticeInfoService constructor.
     *
     * @param \App\Repositories\NoticeInfoRepositoryInterface $noticeInfoRepository
     */
    public function __construct(NoticeInfoRepositoryInterface $noticeInfoRepository)
    {
        $this->noticeInfoRepository = $noticeInfoRepository;
    }
}
