<?php

namespace App\Services;

use App\Repositories\Eloquent\MItemRepository;
use Auth;
use App\Repositories\NoticeInfoRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\NotCorrespondRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;

use Lang;
use Illuminate\Support\Facades\DB;

class NoticeService
{
    /**
     * @var NoticeInfoRepositoryInterface
     */
    protected $noticeInfoRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepository;
    /**
     * @var NotCorrespondRepositoryInterface
     */
    protected $notCorrespondRepository;
    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepository;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenres;
    /**
     * @var MTargetAreaRepositoryInterface
     */
    protected $mTargetArea;
    /**
     * @var MItemService
     */
    protected $mItemService;
    /**
     * @var array
     */
    protected $placeholders = [];
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    protected $mCorpTargetArea;

    /**
     * NoticeService constructor.
     *
     * @param NoticeInfoRepositoryInterface      $noticeInfoRepository
     * @param MCorpRepositoryInterface           $mCorpRepository
     * @param MCorpCategoryRepositoryInterface   $mCorpCategoryRepository
     * @param NotCorrespondRepositoryInterface   $notCorrespondRepository
     * @param MItemRepositoryInterface           $mItemRepository
     * @param MTargetAreaRepositoryInterface     $mTargetArea
     * @param MGenresRepositoryInterface         $mGenres
     * @param MItemService                       $mItemService
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetArea
     */
    public function __construct(
        NoticeInfoRepositoryInterface $noticeInfoRepository,
        MCorpRepositoryInterface $mCorpRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        NotCorrespondRepositoryInterface $notCorrespondRepository,
        MItemRepositoryInterface $mItemRepository,
        MTargetAreaRepositoryInterface $mTargetArea,
        MGenresRepositoryInterface $mGenres,
        MItemService $mItemService,
        MCorpTargetAreaRepositoryInterface $mCorpTargetArea
    ) {
        $this->noticeInfoRepository = $noticeInfoRepository;
        $this->mCorpRepository = $mCorpRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->notCorrespondRepository = $notCorrespondRepository;
        $this->mItemRepository = $mItemRepository;
        $this->mGenres = $mGenres;
        $this->mTargetArea = $mTargetArea;
        $this->mItemService = $mItemService;
        $this->mCorpTargetArea = $mCorpTargetArea;
    }

    /**
     * check role
     *
     * @param string $role
     *
     * @param  array  $roleOption
     * @return boolean
     */
    public function isRole($role, $roleOption)
    {
        return in_array($role, $roleOption) ? true : false;
    }


    /**
     * get list notice infos
     * @param $isRoleAffiliation
     * @param $detailSort
     * @return mixed
     */
    public function getListNoticeInfos($isRoleAffiliation, $detailSort)
    {
        if ($isRoleAffiliation) {
            $user = Auth::user();
            $mCorp = $this->mCorpRepository->getFirstById($user['affiliation_id']);
            return $this->noticeInfoRepository->getListNoticeInfosAff($mCorp, $detailSort);
        } else {
            return $this->noticeInfoRepository->getListNoticeInfos($detailSort);
        }
    }

    /**
     * check display link trader
     *
     * @param  boolean $isRoleAffiliation
     * @return boolean
     */
    public function checkDisplayTrader($isRoleAffiliation)
    {
        $user = Auth::user();
        if ($isRoleAffiliation) {
            return $this->mCorpCategoryRepository
                ->countByCorpIdAndGenreId($user['affiliation_id'], config('rits.FORECAST_CATEGORY_ID')) ? true : false;
        }

        return false;
    }

    /**
     * format detail sort auction setting follow
     *
     * @param  array $data
     * @return array
     */
    public function formatDataNoticeInfoSort($data)
    {
        if (!isset($data['orderBy'])) {
            $detailSort['orderBy'] = 'notice_infos.id';
            $detailSort['orderByDisplay'] = 'notice_infos.id';
            $detailSort['sort'] = 'desc';
        } else {
            $detailSort['orderBy'] = $data['orderBy'];
            $detailSort['orderByDisplay'] = $data['orderBy'];
            if ($data['orderBy'] == 'notice_infos.status') {
                $detailSort['orderBy'] = 'status';
                $detailSort['orderByDisplay'] = 'notice_infos.status';
            }
            $detailSort['sort'] = $data['sort'];
        }
        return $detailSort;
    }

    /**
     * get order by sort item
     *
     * @param  string $sort
     * @param  string $order
     * @param  string $sortValue
     * @return array|string
     */
    public static function getInforOrderSort($sort, $order, $sortValue)
    {
        $isActive = false;
        $isAsc = false;
        if ($sort == $sortValue) {
            $isActive = true;
            if ($order == 'asc') {
                $isAsc = true;
            }
        }
        return [
            'is_active' => $isActive,
            'is_asc' => $isAsc
        ];
    }

    /**
     * get array list item sort follow
     *
     * @param  boolean $isRoleAffiliation
     * @return array
     */
    public function getArrayListItemSortFollow($isRoleAffiliation)
    {
        return [
            [
                'text' => Lang::get('notice_info.bulletin_board_number'),
                'value' => 'notice_infos.id',
            ],
            [
                'text' => Lang::get('notice_info.title'),
                'value' => 'notice_infos.info_title',
            ],
            [
                'text' => Auth::user()->isPoster() ? Lang::get('notice_info.display_target') : Lang::get('notice_info.unread/read'),
                'value' => $isRoleAffiliation ? 'notice_infos.status' : 'notice_infos.corp_commission_type',
            ],
            [
                'text' => Lang::get('notice_info.datetime_regist'),
                'value' => 'notice_infos.created',
            ],
        ];
    }

    /**
     * format date time
     *
     * @param  string $date
     * @param  string $format
     * @return string
     */
    public function dateTimeWeek($date, $format = '%Y/%m/%d(%a)%R')
    {
        if (empty($date)) {
            return "";
        }

        setlocale(LC_TIME, 'ja_JP.utf8');
        return strftime($format, strtotime($date));
    }

    /**
     * @param $corpId
     * @param $data
     * @return boolean
     */
    public function saveTargetAreas($corpId, $data)
    {
        $result = true;
        $regists = $this->convertDataArea($data);
        $saveData = [];
        foreach ($regists as $regist) {
            $corpCategories = $this->getCorpCategory($corpId, $regist['genre_id']);
            foreach ($corpCategories as $corpCategory) {
                if (empty($corpCategory['MCorpCategory__id'])) {
                    $result = false;
                    break 2;
                }

                $targetArea = $this->mTargetArea->getCorpCategoryTargetAreaByJisCd($corpCategory['MCorpCategory__id'], $regist['jis_cd']);
                if (empty($targetArea)) {
                    $saveData[] = [
                        'corp_category_id' => $corpCategory['MCorpCategory__id'],
                        'jis_cd' => $regist['jis_cd'],
                        'address1_cd' => substr($regist['jis_cd'], 0, 2)
                    ];
                }
            }
        }

        if ($result && !empty($saveData)) {
            $saveData = $this->convertDataBeforeSaveTargetArea($saveData);
            $result = $this->saveMTargetArea($saveData, $corpId);
        }

        return $result;
    }

    /**
     * @param $saveData
     * @param $corpId
     * @return bool
     */
    private function saveMTargetArea($saveData, $corpId)
    {
        $result = true;
        try {
            DB::beginTransaction();
            if ($this->mTargetArea->insert($saveData)) {
                $corpTargetAreas = $this->mCorpTargetArea->getListByCorpId($corpId, true);
                $corpCategoryId = $this->getListCorpCategoryId($saveData);
                $saveCategory = $this->getDataSaveMcorpCategory($corpCategoryId, $corpTargetAreas);
                if ($this->mCorpCategoryRepository->updateManyItemWithArray($saveCategory)) {
                    DB::commit();
                    $result = true;
                } else {
                    DB::rollBack();
                    $result = false;
                }
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $result = false;
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getFirstNotCorrespondItem()
    {
        return $this->notCorrespondRepository->getFirstItem()->toArray();
    }

    /**
     * @param $corpId
     * @return mixed
     */
    public function getNotCorrespond($corpId)
    {
        return $this->notCorrespondRepository->findNotCorrespond($corpId);
    }

    /**
     * @return array
     */
    public function getListMitem()
    {
        return $this->mItemService->prepareDataList($this->mItemRepository->getListByCategoryItem(MItemRepository::EXPLOITATION_CATEGORY));
    }

    /**
     * @param $corpId
     * @param $settings
     * @return array
     */
    public function getDataNearNotice($corpId, $settings)
    {
        $corresponds = $this->getNotCorrespond($corpId);
        $developmentGroups = $this->getListMitem();
        $data = [];

        foreach ($developmentGroups as $dkey => $dval) {
            $groups = array_filter(
                $corresponds,
                function ($v) use ($dkey) {
                    return $v['development_group'] == $dkey;
                }
            );

            if (!empty($groups)) {
                $corpGenre = $this->getMGenreByCorpIdAnDevelopmentGroup($corpId, $dkey);
                $isTargetGenre = !empty($corpGenre) ? true: false;
                $isTargetArea = false;
                $correspondData = [
                    'NotCorrespond' => [
                        'development_group' => $dkey,
                        'development_group_name' => $dval,
                        'registed' => $isTargetGenre,
                        'sort' => 0
                    ],
                    'MGenre' => []
                ];
                $data[] = $this->getDataGroups($groups, $isTargetArea, $isTargetGenre, $correspondData, $settings);
            }
        }
        usort($data, function ($data1, $data2) {
            if ($data1['NotCorrespond']['sort'] == $data2['NotCorrespond']['sort']) {
                return $data1['NotCorrespond']['development_group'] < $data2['NotCorrespond']['development_group'] ? -1 : 1;
            }
            return $data1['NotCorrespond']['sort'] < $data2['NotCorrespond']['sort'] ? -1 : 1;
        });
        return $data;
    }

    /**
     * @param $corpId
     * @param $dkey
     * @return mixed
     */
    private function getMGenreByCorpIdAnDevelopmentGroup($corpId, $dkey)
    {
        return $this->mGenres->getMGenreByCorpIdAnDevelopmentGroup($corpId, $dkey);
    }

    /**
     * @param $groups
     * @param $isTargetArea
     * @param $isTargetGenre
     * @param $correspondData
     * @param $settings
     * @return mixed
     */
    private function getDataGroups($groups, $isTargetArea, $isTargetGenre, $correspondData, $settings)
    {
        foreach ($groups as $val) {
            $isTargetArea = $isTargetArea || !empty($val['NotCorrespond__target_corp_area']);
            $genreId = $val['genre_id'];
            if (!array_key_exists($genreId, $correspondData['MGenre'])) {
                $genreUnitPrices = config('genre_unit_prices');
                $unitPrice = !empty($genreUnitPrices[$genreId]) ? $genreUnitPrices[$genreId] : 0;

                $correspondData['MGenre'][$genreId] = [
                    'genre_id' => $genreId,
                    'genre_name' => $val['genre_name'],
                    'unit_price' => $unitPrice,
                    'registed' => $val['NotCorrespond__target_category'] > 0,
                    'Correspond' => [],
                ];
            }

            $prefectureCd = $val['prefecture_cd'];
            if (!array_key_exists($prefectureCd, $correspondData['MGenre'][$genreId]['Correspond'])) {
                $correspondData['MGenre'][$genreId]['Correspond'][$prefectureCd] = [
                    'address1' => $val['address1'],
                    'Area' => []
                ];
            }
            $correspondData['MGenre'][$genreId]['Correspond'][$prefectureCd]['Area'][] = [
                'jis_cd' => $val['jis_cd'],
                'address1' => $val['address1'],
                'address2' => $val['address2'],
                'count' => $val['not_correspond_count_year'],
                'new_flg' => date('Y-m-d', strtotime($val['NotCorrespond__min_import_date'])) > date('Y-m-d', strtotime('-1week'))
                    ? true : false,
                'sort' => $this->getNearlySort(
                    $settings,
                    $val['not_correspond_count_year'],
                    $val['not_correspond_count_latest']
                )
            ];
        }

        return $this->getCorrespondData($correspondData, $isTargetArea, $isTargetGenre);
    }

    /**
     * @param $correspondData
     * @param $isTargetArea
     * @param $isTargetGenre
     * @return mixed
     */
    private function getCorrespondData($correspondData, $isTargetArea, $isTargetGenre)
    {
        $correspondData = $this->sortCorrespondData($correspondData);

        usort($correspondData['MGenre'], function ($genre1, $genre2) {
            if ($genre1['registed'] == $genre2['registed']) {
                return $genre1['genre_id'] < $genre2['genre_id'] ? -1 : 1;
            }
            return $genre1['registed'] < $genre2['registed'] ? 1 : -1;
        });
        $correspondData['NotCorrespond']['sort'] = $this->getGroupSort($isTargetGenre, $isTargetArea);
        return $correspondData;
    }

    /**
     * @param $correspondData
     * @return mixed
     */
    private function sortCorrespondData($correspondData)
    {
        foreach ($correspondData['MGenre'] as $gVal) {
            foreach ($gVal['Correspond'] as $cVal) {
                usort($cVal['Area'], function ($area1, $area2) {
                    if ($area1['sort'] == $area2['sort']) {
                        if ($area1['count'] == $area2['count']) {
                            return $area1['jis_cd'] < $area2['jis_cd'] ? -1 : 1;
                        } else {
                            return $area1['count'] < $area2['count'] ? 1 : -1;
                        }
                    }

                    return $area1['sort'] < $area2['sort'] ? -1 : 1;
                });
            }
        }
        return $correspondData;
    }

    /**
     * @param $settings
     * @param $countYear
     * @param $countLatest
     * @return integer
     */
    private function getNearlySort($settings, $countYear, $countLatest)
    {
        if ($countLatest >= $settings['immediate_lower_limit']) {
            return 0;
        } elseif ($countYear >= $settings['large_lower_limit']) {
            return 1;
        } elseif ($countYear >= $settings['midium_lower_limit']) {
            return 2;
        } else {
            return 3;
        }
    }

    /**
     * @param $isTargetGenre
     * @param $isTargetArea
     * @return integer
     */
    private function getGroupSort($isTargetGenre, $isTargetArea)
    {
        if ($isTargetGenre) {
            return 1;
        } elseif (!$isTargetGenre && $isTargetArea) {
            return 2;
        } elseif (!$isTargetGenre && !$isTargetArea) {
            return 3;
        }
        return 9;
    }

    /**
     * @param $data
     * @return array
     */
    private function convertDataArea($data)
    {
        $regists = [];
        foreach ($data['id'] as $id) {
            $ids = explode('-', $id);
            $regists[] = [
                'genre_id' => $ids[0],
                'jis_cd' => $ids[1],
            ];
        }
        return $regists;
    }

    /**
     * @param null $corpId
     * @param null $genreId
     * @return mixed
     */
    private function getCorpCategory($corpId = null, $genreId = null)
    {
        return $this->mGenres->getListByCorpIdAndGenreId($corpId, $genreId);
    }

    /**
     * @param $text
     * @return string
     */
    public function autoLink($text)
    {
        $text = $this->autoLinkUrls($text);
        return $this->autoLinkEmails($text);
    }


    /**
     * @param $text
     * @return string
     */
    public function autoLinkUrls($text)
    {
        $this->placeholders = [];

        $pattern = '#(?<!href="|src="|">)((?:https?|ftp|nntp)://[\p{L}0-9.\-_:]+(?:[/?][^\s<]*)?)#ui';
        $text = preg_replace_callback(
            $pattern,
            [&$this, 'insertPlaceHolder'],
            $text
        );
        $text = preg_replace_callback(
            '#(?<!href="|">)(?<!\b[[:punct:]])(?<!http://|https://|ftp://|nntp://)www.[^\n\%\ <]+[^<\n\%\,\.\ <](?<!\))#i',
            [&$this, 'insertPlaceHolder'],
            $text
        );
        return $this->linkUrls($text);
    }

    /**
     * @param $text
     * @return string
     */
    protected function linkUrls($text)
    {
        $replace = [];
        foreach ($this->placeholders as $hash => $url) {
            $link = $url;
            if (!preg_match('#^[a-z]+\://#', $url)) {
                $url = 'http://' . $url;
            }
            $replace[$hash] = '<a href="' . $url . '" target=_blank>' . $link . '</a>';
        }
        return strtr($text, $replace);
    }

    /**
     * @param $text
     * @return string
     */
    public function autoLinkEmails($text)
    {
        $this->placeholders = [];

        $atom = '[\p{L}0-9!#$%&\'*+\/=?^_`{|}~-]';
        $text = preg_replace_callback(
            '/(?<=\s|^|\(|\>|\;)(' . $atom . '*(?:\.' . $atom . '+)*@[\p{L}0-9-]+(?:\.[\p{L}0-9-]+)+)/ui',
            [&$this, 'insertPlaceHolder'],
            $text
        );
        return $this->linkEmails($text);
    }

    /**
     * @param $text
     * @return string
     */
    protected function linkEmails($text)
    {
        $replace = [];
        foreach ($this->placeholders as $hash => $url) {
            $replace[$hash] = '<a href="mailto:'. $url .'">' . $url . '</a>';
        }
        return strtr($text, $replace);
    }

    /**
     * @param $matches
     * @return string
     */
    protected function insertPlaceHolder($matches)
    {
        $key = md5($matches[0]);
        $this->placeholders[$key] = $matches[0];
        return $key;
    }

    /**
     * format corp commission type
     *
     * @param  $listItemNotice
     * @param  $optionBlank
     * @return array $listItemNotice
     */
    public function formatCorpCommissionType($listItemNotice, $optionBlank)
    {
        $arrayOption = [
            '' => $optionBlank,
        ];
        foreach ($listItemNotice as $key => $value) {
            $arrayOption[$key] = $value;
        }
        return $arrayOption;
    }

    /**
     * @param $saveData
     * @return array
     */
    private function getListCorpCategoryId($saveData)
    {
        $corpCategoryId = [];
        foreach ($saveData as $val) {
            if (! array_key_exists($val['corp_category_id'], $corpCategoryId)) {
                $corpCategoryId[] = $val['corp_category_id'];
            }
        }
        return $corpCategoryId;
    }

    /**
     * @param $corpCategoryId
     * @param $corpTargetAreas
     * @return array
     */
    private function getDataSaveMcorpCategory($corpCategoryId, $corpTargetAreas)
    {
        $saveCategory = [];
        foreach ($corpCategoryId as $val) {
            $taCount = count($corpTargetAreas);
            $ctaCount = $this->mTargetArea->getCorpCategoryTargetAreaCount3($val);
            $targetAreaType = 2;
            if ($taCount == $ctaCount) {
                $jisCds = [];
                foreach ($corpTargetAreas as $corpTargetArea) {
                    $jisCds[] = $corpTargetArea['jis_cd'];
                }
                $defaultCount = $this->mTargetArea->getCorpCategoryTargetAreaCount2($val, $jisCds);
                if ($taCount == $defaultCount) {
                    $targetAreaType = 1;
                }
            }
            $saveCategory[] = [
                'id' => $val,
                'target_area_type' => $targetAreaType,
            ];
        }
        return $saveCategory;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function convertDataBeforeSaveTargetArea($data)
    {
        foreach ($data as $element) {
            $element['modified_user_id'] = Auth::user()->user_id;
            $element['modified'] = date("Y-m-d H:i:s");
            $element['created_user_id'] = Auth::user()->user_id;
            $element['created'] = date("Y-m-d H:i:s");
        }
        return $data;
    }
}
