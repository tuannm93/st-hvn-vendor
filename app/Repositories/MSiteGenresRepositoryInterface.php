<?php

namespace App\Repositories;

interface MSiteGenresRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Get mSite genres drop down by site Id
     * @param integer $siteId
     * @return mixed
     */
    public function getMSiteGenresDropDownBySiteId($siteId = null);

    /**
     * @param integer $siteId
     * @param bool $hideFlg
     * @return mixed
     */
    public function getGenreBySiteStHide($siteId, $hideFlg = true);

    /**
     * @param integer $siteId
     * @return mixed
     */
    public function getGenreBySite($siteId);

    /**
     * @param integer $siteId
     * @return mixed
     */
    public function getGenreRankBySiteId($siteId);
}
