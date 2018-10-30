<?php

namespace App\Repositories;

interface AffiliationStatsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Acquisition of statistical information by franchise store genre
     *
     * @param  $corpId
     * @return \Illuminate\Support\Collection
     */
    public function getAffiliationStatsList($corpId);

    /**
     * @param integer $id
     * @param array $data
     * @return \App\Models\Base
     */
    public function update($id, $data = []);

    /**
     * Get first AffiliationStats by corpId and genreId
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $corpId
     * @param integer $genreId
     * @return mixed
     */
    public function getByCorpIdAndGenreId($corpId, $genreId);

    /**
     * Get affiliation_stats by group category
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getCommissionGroupCategoryCountInitialize();

    /**
     * Get affiliation_stats by group category and commission_status
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $status
     * @return mixed
     */
    public function getCommissionGroupCategoryOrderCountInitialize($status);

    /**
     * Get affiliation_stats join shell_work_result
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithJoinShellWork();
}
