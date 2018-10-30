<?php

namespace App\Repositories\Eloquent;

use App\Models\MSite;
use App\Models\MSiteGenres;
use App\Repositories\MSiteGenresRepositoryInterface;

class MSiteGenresRepository extends SingleKeyModelRepository implements MSiteGenresRepositoryInterface
{
    /**
     * @var MSiteGenres
     */
    protected $model;
    /**
     * @var MSite
     */
    protected $mSite;

    /**
     * MSiteGenresRepository constructor.
     *
     * @param MSiteGenres $model
     * @param MSite       $mSite
     */
    public function __construct(MSiteGenres $model, MSite $mSite)
    {
        $this->model = $model;
        $this->mSite = $mSite;
    }

    /**
     * @return \App\Models\Base|MSiteGenres|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MSiteGenres();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }

    /**
     * @param integer $siteId
     * @return array|\Illuminate\Config\Repository|mixed
     */
    public function getMSiteGenresDropDownBySiteId($siteId = null)
    {
        $defaultOption = config('constant.defaultOption');
        if ($siteId == null) {
            return $defaultOption;
        }

        $mSite = $this->mSite->find($siteId);
        if (!$mSite) {
            return $defaultOption;
        }

        $query = $this->model->join(
            'm_genres',
            function ($join) {
                $join->on('m_genres.id', 'm_site_genres.genre_id');
            }
        )->select('m_genres.genre_name', 'm_genres.id')->orderBy('id', 'asc');
        if (isset($mSite) && $mSite->cross_site_flg != 1) {
            $query->where('site_id', $siteId);
        }

        return ['' => '--なし--'] + $query->pluck('m_genres.genre_name', 'm_genres.id')->toArray();
    }

    /**
     * Get genre by site id
     * {@inheritDoc}
     *
     * @see \App\Repositories\MSiteGenresRepositoryInterface::getGenreBySiteStHide()
     */
    public function getGenreBySiteStHide($siteId, $hideFlg = true)
    {
        $mSite = $this->mSite->find($siteId);
        $query = $this->model->select('m_genres.genre_name', 'm_genres.id')
            ->join(
                'm_genres',
                function ($join) use ($hideFlg) {
                    $join->on('m_site_genres.genre_id', '=', 'm_genres.id');

                    if ($hideFlg) {
                        $join->where('m_genres.st_hide_flg', 0);
                    }
                }
            );

        if ($mSite['cross_site_flg'] != 1) {
            $query->where('m_site_genres.site_id', $siteId);
        }

        return $query->orderBy('m_genres.id', 'asc')->pluck('m_genres.genre_name', 'm_genres.id');
    }

    /**
     * Get genre by site id with st_hide_flg = false
     *
     * {@inheritDoc}
     *
     * @see \App\Repositories\MSiteGenresRepositoryInterface::getGenreBySite()
     */
    public function getGenreBySite($siteId)
    {
        $result = null;

        if (!empty($siteId)) {
            $result = $this->getGenreBySiteStHide($siteId, false);
        }

        return $result;
    }

    /**
     * Get genre rank by site id
     * {@inheritDoc}
     *
     * @see \App\Repositories\MSiteGenresRepositoryInterface::getGenreByRank()
     */
    public function getGenreRankBySiteId($siteId)
    {
        $query = $this->model->from('m_site_genres AS MSiteGenre')
            ->join(
                'm_genres AS MGenre',
                function ($join) {
                                    $join->on('MSiteGenre.genre_id', '=', 'MGenre.id');
                }
            );

        if ($siteId != null) {
            $query->where('MSiteGenre.site_id', $siteId);
        }

        $rows = $query->select(
            'MGenre.id AS MGenre__id',
            'MGenre.commission_rank AS MGenre__commission_rank'
        )
            ->orderBy('MGenre.id', 'asc')
            ->get()->toArray();
        $result = [];

        foreach ($rows as $row) {
            $result[$row['MGenre__id']] = $row['MGenre__commission_rank'];
        }

        return $result;
    }
}
