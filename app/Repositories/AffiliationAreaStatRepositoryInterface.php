<?php

namespace App\Repositories;

interface AffiliationAreaStatRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * find by corp id and gener id and prefecture
     *
     * @param  integer $corpId
     * @param  integer $genreId
     * @param  string  $prefecture
     * @return object
     */
    public function findByCorpIdAndGenerIdAndPrefecture($corpId, $genreId, $prefecture);

    /**
     * @param \App\Models\Base|array|object $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * get data by corp_id,genre_id and prefecture
     * @param array $data
     * @param integer $prefecture
     * @return mixed
     */
    public function getByPrefecture($data, $prefecture);

    /**
     * @param array $data
     * @param integer $prefecture
     * @return mixed
     */
    public function insertBy($data, $prefecture);

    /**
     * insert data
     * @param null $data
     * @return mixed
     */
    public function getMaxCommissionUnitPriceCategory($data = null);

    /**
     * Update data by id
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $id
     * @param  array            $data
     * @return \App\Models\Base|boolean
     */
    public function update($id, $data);

    /**
     * Find by corp_id and genre_id
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $corpId
     * @param integer $genreId
     * @return mixed
     */
    public function finByCorpIdAndGenreId($corpId, $genreId);

    /**
     * Get list affiliation_area_stats join subQuery
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getGroupCategoryCountInitialize();

    /**
     * Get list affiliation_area_stats order by corp_id, genre_id, address1
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithJoinShellWork();
}
