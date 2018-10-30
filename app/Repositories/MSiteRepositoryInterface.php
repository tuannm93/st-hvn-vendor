<?php

namespace App\Repositories;

interface MSiteRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $siteTel
     * @return mixed
     */
    public function searchMsite($siteTel);

    /**
     * @param array $data
     * @return mixed
     */
    public function findMaxLimit($data);

    /**
     * @return mixed
     */
    public function getList();

    /**
     * @param bool $flg
     * @return mixed
     */
    public function getCrossSiteFlg($flg);

    /**
     * @return mixed
     */
    public function getListMSitesForDropDown();

    /**
     * @param string $siteName
     * @return mixed
     */
    public function getSiteByName($siteName);

    /**
     * get one site by site's tel
     * @param  int $siteTel site tel
     * @return mixed
     */
    public function getFirstSiteByTel($siteTel);

    /**
     * Return String list site
     * Use in DemandInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $id
     * @return string
     */
    public function getListText($id);
}
