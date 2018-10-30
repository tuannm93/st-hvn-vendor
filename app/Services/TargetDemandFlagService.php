<?php

namespace App\Services;

use App\Repositories\MGenresRepositoryInterface;


use Illuminate\Support\Facades\DB;
use Exception;

class TargetDemandFlagService
{
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenresRepository;

    /**
     * TargetDemandFlagService constructor.
     *
     * @param MGenresRepositoryInterface $mGenresRepository
     */
    public function __construct(MGenresRepositoryInterface $mGenresRepository)
    {
        $this->mGenresRepository = $mGenresRepository;
    }

    /**
     * @param $exclusionFlg
     * @return boolean
     */
    public function updateDemandFlag($exclusionFlg)
    {
        try {
            DB::beginTransaction();

            $oldData = $this->mGenresRepository->getGenreWithConditions(['valid_flg' => 1]);
            $oldCheckedData = $oldData->mapToGroups(
                function ($item) {
                    return [$item['exclusion_flg'] => $item['id']];
                }
            )->toArray();
            $checkedFlg = array_diff($exclusionFlg, $oldCheckedData[1]);
            $unCheckedFlg = array_diff($oldCheckedData[1], $exclusionFlg);

            if (!empty($checkedFlg)) {
                foreach ($checkedFlg as $id) {
                    $this->mGenresRepository->updateExclusionFlg($id, 1);
                }
            }

            if (!empty($unCheckedFlg)) {
                foreach ($unCheckedFlg as $id) {
                    $this->mGenresRepository->updateExclusionFlg($id, 0);
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            logger(__METHOD__ . 'Error : ' . $e->getMessage());
            return false;
        }
    }
}
