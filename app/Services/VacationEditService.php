<?php

namespace App\Services;

use App\Repositories\MCorpNewYearRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use Illuminate\Support\Facades\DB;

class VacationEditService
{
    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepo;

    /**
     * @var MCorpNewYearRepositoryInterface
     */
    protected $mCorpNewYearRepo;

    /**
     * VacationEditService constructor.
     *
     * @param MItemRepositoryInterface        $mItemRepo
     * @param MCorpNewYearRepositoryInterface $mCorpNewYearRepository
     */
    public function __construct(
        MItemRepositoryInterface $mItemRepo,
        MCorpNewYearRepositoryInterface $mCorpNewYearRepository
    ) {
        $this->mItemRepo = $mItemRepo;
        $this->mCorpNewYearRepo = $mCorpNewYearRepository;
    }

    /**
     * Function delete m_corp_new_years, delete m_items and create new
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  $data
     * @return boolean
     * @throws \Exception
     */
    public function update($data)
    {
        DB::beginTransaction();
        try {
            $this->mCorpNewYearRepo->deleteAll();
            $this->mItemRepo->deleteByLongHoliday();
            $this->mItemRepo->insert($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();

            return false;
        }

        return true;
    }
}
