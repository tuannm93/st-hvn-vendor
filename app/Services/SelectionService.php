<?php

namespace App\Services;

use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\SelectionGenreRepositoryInterface;
use App\Repositories\SelectionGenrePrefectureRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class SelectionService
{
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenresRepository;
    /**
     * @var SelectionGenreRepositoryInterface
     */
    protected $selectGenreRepository;

    /**
     * SelectionService constructor.
     *
     * @param MGenresRepositoryInterface                  $mGenresRepository
     * @param SelectionGenreRepositoryInterface           $selectGenreRepository
     * @param SelectionGenrePrefectureRepositoryInterface $selectGenrePreRepository
     */
    public function __construct(
        MGenresRepositoryInterface $mGenresRepository,
        SelectionGenreRepositoryInterface $selectGenreRepository,
        SelectionGenrePrefectureRepositoryInterface $selectGenrePreRepository
    ) {
        $this->mGenresRepository = $mGenresRepository;
        $this->selectGenreRepository = $selectGenreRepository;
        $this->selectGenrePreRepository = $selectGenrePreRepository;
    }

    /**
     * @return mixed
     */
    public function getSelectionGenre()
    {
        $data = $this->mGenresRepository->getSelectionGenre();
        return $data;
    }

    /**
     * @param $allData
     * @return boolean
     */
    public function saveAllSelectGenre($allData)
    {
        try {
            DB::beginTransaction();
            foreach ($allData as $data) {
                $save = $this->selectGenreRepository->updateOrSave($data, $data['id']);
                if ($save) {
                    continue;
                } else {
                    DB::rollBack();
                    return false;
                }
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            logger(__METHOD__. 'Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @param $allData
     * @return boolean
     */
    public function saveAllSelectGenrePrefecture($id, $allData)
    {
        try {
            DB::beginTransaction();
            $this->selectGenrePreRepository->deleteBaseOnGenreId($id);
            foreach ($allData as $data) {
                if (isset($data['prefecture_cd'])) {
                    $save = $this->selectGenrePreRepository->saveNewSelectionGenrePrefecture($data);
                    if ($save) {
                        continue;
                    } else {
                        DB::rollBack();
                        return false;
                    }
                }
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            logger(__METHOD__. 'Error: ' . $e->getMessage());
            return false;
        }
    }
}
