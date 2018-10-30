<?php

namespace App\Services;

use App\Repositories\NotCorrespondItemRepositoryInterface;

const CORRESPOND_ITEM_ID = 1;
class NotCorrespondItemService
{
    /**
     * @var NotCorrespondItemRepositoryInterface
     */
    protected $notCorrespondItemsRepository;

    /**
     * NotCorrespondItemService constructor.
     *
     * @param NotCorrespondItemRepositoryInterface $notCorrespondItemsRepository
     */
    public function __construct(
        NotCorrespondItemRepositoryInterface $notCorrespondItemsRepository
    ) {
        $this->notCorrespondItemsRepository = $notCorrespondItemsRepository;
    }

    /**
     * @return \App\Models\Base|null
     */
    public function getCorrespondItem()
    {
        return $this->notCorrespondItemsRepository->find(CORRESPOND_ITEM_ID);
    }

    /**
     * @param $id
     * @param $data
     * @return boolean
     */
    public function update($id, $data)
    {
        try {
            $item = $this->notCorrespondItemsRepository->find($id);
            if ($item !== null) {
                $data['modified_user_id'] = \Auth::user()->user_id;
                $data['modified'] = \Carbon\Carbon::now();
                $this->notCorrespondItemsRepository->update($item, $data);
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
