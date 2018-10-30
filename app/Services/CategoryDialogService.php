<?php


namespace App\Services;

use App\Models\MCorpCategoriesTemp;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MCategoryCopyRuleRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class CategoryDialogService
{
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgreementTempLinkRepo;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTempRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepository;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenresRepository;
    /**
     * @var MCategoryCopyRuleRepositoryInterface
     */
    protected $mCategoryCopyRuleRepository;

    /**
     * CategoryDialogService constructor.
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository
     * @param MCategoryRepositoryInterface $mCategoryRepository
     * @param MGenresRepositoryInterface $mGenresRepository
     * @param MCategoryCopyRuleRepositoryInterface $mCategoryCopyRuleRepository
     */
    public function __construct(
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        MGenresRepositoryInterface $mGenresRepository,
        MCategoryCopyRuleRepositoryInterface $mCategoryCopyRuleRepository
    ) {
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->corpAgreementTempLinkRepo = $corpAgreementTempLinkRepo;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->mGenresRepository = $mGenresRepository;
        $this->mCategoryCopyRuleRepository = $mCategoryCopyRuleRepository;
    }

    /**
     * @param $corpId
     * @return array
     */
    public function getListCategoryDialog($corpId)
    {
        $tempLink = $this->corpAgreementTempLinkRepo->findLatestByCorpId($corpId);
        $mCorpCategoriesTemp = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndTempIdWithFlag(
            $corpId,
            $tempLink->id,
            false,
            false
        );
        $categoryList = $this->mCategoryRepository->findAllForAffiliation()->load('mGenres');

        $mCorpCategoriesTempArray = [];
        if (!checkIsNullOrEmptyCollection($mCorpCategoriesTemp)) {
            $mCorpCategoriesTempArray = $mCorpCategoriesTemp->keyBy('category_id')->toArray();
        }
        $genreGroupBase = $this->buildGenreGroupList();
        $genreGroupIntro = $this->buildGenreGroupList();
        $genreGroupName = [];

        foreach ($categoryList as $category) {
            $this->analyseDataGenre(
                $category,
                $genreGroupBase,
                $genreGroupIntro,
                $genreGroupName,
                $mCorpCategoriesTempArray
            );
        }
        $genreArray = [];
        $genreArray['genreGroupBase'] = $genreGroupBase;
        $genreArray['genreGroupIntro'] = $genreGroupIntro;
        $genreArray['genreGroupName'] = $genreGroupName;
        return $genreArray;
    }

    /**
     * @return array
     */
    private function buildGenreGroupList()
    {
        $genreGroupList = [];
        $genreGroup = Config::get('datacustom.genre_group');
        foreach ($genreGroup as $index => $value) {
            $genreGroupList[$index] = $this->buildMGenres($value);
        }
        return $genreGroupList;
    }

    /**
     * @param $name
     * @return array
     */
    private function buildMGenres($name)
    {
        $entity = [];
        $entity['genreGroup'] = $name;
        $entity['sepCount'] = 1;
        $entity['categoryCount'] = 0;
        $entity['categoryList1'] = [];
        $entity['categoryList2'] = [];
        $entity['categoryList3'] = [];

        return $entity;
    }

    /**
     * Split genres of company to 2 groups ( Base and Intro)
     * @param $category
     * @param $genreGroupBase
     * @param $genreGroupIntro
     * @param $genreNameArray
     * @param $mCorpCategoriesTempArray
     */
    private function analyseDataGenre(
        $category,
        &$genreGroupBase,
        &$genreGroupIntro,
        &$genreNameArray,
        $mCorpCategoriesTempArray
    ) {
        $genre = $category->mGenres;
        $genreGroup = $genre->genre_group;
        if (!is_null($genreGroup) && $genreGroup > 0) {
            if ($genre->commission_type == 1) {
                //base
                $genreGroupBase[$genreGroup] = $this->convertDataGenre(
                    $category,
                    $genreGroupBase[$genreGroup],
                    $genreNameArray,
                    $mCorpCategoriesTempArray
                );
            } elseif ($genre->commission_type == 2) {
                //intro
                $genreGroupIntro[$genreGroup] = $this->convertDataGenre(
                    $category,
                    $genreGroupIntro[$genreGroup],
                    $genreNameArray,
                    $mCorpCategoriesTempArray
                );
            }
        }
    }

    /**
     * put data into genreDTO in order to show in UI
     * @param $category
     * @param $genreDTO
     * @param $genreNameArray
     * @param $mCorpCategoriesTempArray
     * @return mixed
     */
    private function convertDataGenre($category, $genreDTO, &$genreNameArray, $mCorpCategoriesTempArray)
    {
        $categoryDTO = [];
        $categoryDTO['genreName'] = $category->genre_name;
        $categoryDTO['categoryName'] = $category->category_name;
        $categoryDTO['categoryId'] = $category->m_category_id;
        $categoryDTO['selectList'] = '';
        if (array_key_exists($category->m_category_id, $mCorpCategoriesTempArray)) {
            $mCorpCategoriesTemp = $mCorpCategoriesTempArray[$category->m_category_id];
            $categoryDTO['mCorpCategoryTempId'] = $mCorpCategoriesTemp['m_corp_categories_temp_id'];
            $categoryDTO['selectList'] = $mCorpCategoriesTemp['select_list'];
            if (!array_key_exists($category->genre_name, $genreNameArray)) {
                $genreNameArray[$category->genre_name] = $category->genre_name;
            }
        }
        //divide 3 lists in order to show in UI
        if ($genreDTO['sepCount'] === 1) {
            array_push($genreDTO['categoryList1'], $categoryDTO);
            $genreDTO['sepCount']++;
        } elseif ($genreDTO['sepCount'] === 2) {
            array_push($genreDTO['categoryList2'], $categoryDTO);
            $genreDTO['sepCount']++;
        } elseif ($genreDTO['sepCount'] === 3) {
            array_push($genreDTO['categoryList3'], $categoryDTO);
            $genreDTO['sepCount'] = 1;
        }
        $genreDTO['categoryCount']++;
        return $genreDTO;
    }

    /**
     * @param $data
     * @param $corpId
     */
    public function postListCategoryDialog($data, $corpId)
    {
        $categoryList = $this->mCategoryRepository->findAllForAffiliation()->load('mGenres');
        $tempLink = $this->corpAgreementTempLinkRepo->findLatestByCorpId($corpId);
        $listUpdateCategoryId = [];

        foreach ($categoryList as $category) {
            try {
                $this->executeCorpCategory($category, $data, $corpId, $tempLink->id, $listUpdateCategoryId);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
        }
    }

    /**
     * update, insert new, delete categories which the affiliation of company supports
     * @param $category
     * @param $data
     * @param $corpId
     * @param $tempLinkId
     * @param $listUpdateCategoryId
     */
    private function executeCorpCategory($category, $data, $corpId, $tempLinkId, &$listUpdateCategoryId)
    {
        $keyCategoryId = 'categoryId_' . $category->m_category_id;
        $keySelectList = 'selectList_' . $category->m_category_id;
        $mCorpCategories = $this->mCorpCategoriesTempRepository->getFirstByCorpIdAndCateIdAndTempIdAndDelFlag(
            $corpId,
            $category->m_category_id,
            $tempLinkId,
            false
        );
        if (is_null($mCorpCategories) && array_key_exists($keyCategoryId, $data)) {
            $selectList = $data[$keySelectList];
            //insert
            $this->insertNewCorpCategoryTemp($category, $selectList, $corpId, $tempLinkId, $listUpdateCategoryId);
        } elseif (!is_null($mCorpCategories)) {
            if (!array_key_exists($keyCategoryId, $data)) {
                if (!array_key_exists($category->m_category_id, $listUpdateCategoryId)) {
                    $this->deleteCorpCategoryTemp($mCorpCategories, $category, $corpId, $tempLinkId);
                }
            } else {
                $selectList = $data[$keySelectList];
                $this->updateCorpCategoryTemp($mCorpCategories, $selectList, $listUpdateCategoryId);
            }
        }
    }

    /**
     * @param $category
     * @param $selectList
     * @param $corpId
     * @param $tempLinkId
     * @param $listUpdateCategoryId
     */
    private function insertNewCorpCategoryTemp($category, $selectList, $corpId, $tempLinkId, &$listUpdateCategoryId)
    {
        $mCorpCategoryTemp = $this->createObjectMCorpCategoryTempDefault($category, $corpId, $tempLinkId);
        $mCorpCategoryTemp->category_id = $category->m_category_id;
        $genre = $category->mGenres;
        if ($genre->commission_type == 2) {
            $mCorpCategoryTemp->introduce_fee = $category->category_default_fee;
        } else {
            $mCorpCategoryTemp->order_fee = $category->category_default_fee;
            $mCorpCategoryTemp->order_fee_unit = $category->category_default_fee_unit;
        }
        $mCorpCategoryTemp->corp_commission_type = $genre->commission_type;
        $mCorpCategoryTemp->select_list = $selectList;
        $this->mCorpCategoriesTempRepository->save($mCorpCategoryTemp);
        $listUpdateCategoryId[$mCorpCategoryTemp->category_id] = $mCorpCategoryTemp->category_id;
        $this->insertCategoryCopyRule($category, $corpId, $tempLinkId, $listUpdateCategoryId);
    }

    /**
     * @param $category
     * @param $corpId
     * @param $tempLinkId
     * @return MCorpCategoriesTemp
     */
    private function createObjectMCorpCategoryTempDefault($category, $corpId, $tempLinkId)
    {
        $mCorpCategoryTemp = new MCorpCategoriesTemp();
        $mCorpCategoryTemp->corp_id = $corpId;
        $mCorpCategoryTemp->genre_id = $category->genre_id;
        $mCorpCategoryTemp->target_area_type = 1;
        $mCorpCategoryTemp->created_user_id = Auth::user()->user_id;
        $mCorpCategoryTemp->created = Carbon::now()->toDateTimeString();
        $mCorpCategoryTemp->modified_user_id = $mCorpCategoryTemp->created_user_id;
        $mCorpCategoryTemp->modified = $mCorpCategoryTemp->created;
        $mCorpCategoryTemp->temp_id = $tempLinkId;
        $mCorpCategoryTemp->delete_flag = false;
        $mCorpCategoryTemp->version_no = 1;
        $mCorpCategoryTemp->select_genre_category = null;

        return $mCorpCategoryTemp;
    }

    /**
     * Some categories link other category. So they will be inserted or deleted together
     * @param $category
     * @param $corpId
     * @param $tempLinkId
     * @param $listUpdateCategoryId
     */
    private function insertCategoryCopyRule($category, $corpId, $tempLinkId, &$listUpdateCategoryId)
    {
        $listCategoryCopyRule = $this->mCategoryCopyRuleRepository->findAllByOrgCategoryId($category->m_category_id);
        foreach ($listCategoryCopyRule as $categoryCopyRule) {
            $registeredCopyCategory = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndCateIdAndTempIdAndDelFlag(
                $corpId,
                $categoryCopyRule->copy_category_id,
                $tempLinkId,
                false
            );
            if (is_null($registeredCopyCategory)) {
                $mCorpCategoryTemp = $this->createObjectMCorpCategoryTempDefault($category, $corpId, $tempLinkId);
                $mCorpCategoryTemp->category_id = $categoryCopyRule->copy_category_id;
                $mCorpCategoryTemp->genre_id = $categoryCopyRule->genre_id;

                if ($categoryCopyRule->commission_type == 2) {
                    $mCorpCategoryTemp->introduce_fee = $categoryCopyRule->category_default_fee;
                } else {
                    $mCorpCategoryTemp->order_fee = $categoryCopyRule->category_default_fee;
                    $mCorpCategoryTemp->order_fee_unit = $categoryCopyRule->category_default_fee_unit;
                }
                $mCorpCategoryTemp->corp_commission_type = $categoryCopyRule->commission_type;
                $this->mCorpCategoriesTempRepository->save($mCorpCategoryTemp);
                $listUpdateCategoryId[$categoryCopyRule->copy_category_id] = $categoryCopyRule->copy_category_id;
            }
        }
    }

    /**
     * @param $mCorpCategoryTemp
     * @param $category
     * @param $corpId
     * @param $tempLinkId
     */
    private function deleteCorpCategoryTemp($mCorpCategoryTemp, $category, $corpId, $tempLinkId)
    {
        $mCorpCategoryTempUpdate = $this->createObjectDeleteMCorpCategoryTempDefault($mCorpCategoryTemp);
        $this->mCorpCategoriesTempRepository->save($mCorpCategoryTempUpdate);
        $this->deleteCategoryCopyRule($category, $corpId, $tempLinkId);
    }

    /**
     * @param $mCorpCategoryTemp
     * @return \App\Models\Base|null
     */
    private function createObjectDeleteMCorpCategoryTempDefault($mCorpCategoryTemp)
    {
        $mCorpCategoryTempUpdate = $this->mCorpCategoriesTempRepository->find($mCorpCategoryTemp->m_corp_categories_temp_id);
        $mCorpCategoryTempUpdate->version_no = $mCorpCategoryTempUpdate->version_no + 1;
        $mCorpCategoryTempUpdate->delete_flag = true;
        $mCorpCategoryTempUpdate->delete_date = Carbon::now()->toDateTimeString();
        $mCorpCategoryTempUpdate->modified_user_id = Auth::user()->user_id;
        $mCorpCategoryTempUpdate->modified = $mCorpCategoryTempUpdate->delete_date;
        return $mCorpCategoryTempUpdate;
    }

    /**
     * @param $category
     * @param $corpId
     * @param $tempLinkId
     */
    private function deleteCategoryCopyRule($category, $corpId, $tempLinkId)
    {
        $listCategoryCopyRule = $this->mCategoryCopyRuleRepository->findAllByOrgCategoryId($category->m_category_id);
        foreach ($listCategoryCopyRule as $categoryCopyRule) {
            $registeredCopyCategory = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndCateIdAndTempIdAndDelFlag(
                $corpId,
                $categoryCopyRule->copy_category_id,
                $tempLinkId,
                false
            );
            if (!is_null($registeredCopyCategory)) {
                $registeredCopyCategory = $this->createObjectDeleteMCorpCategoryTempDefault($registeredCopyCategory);
                $this->mCorpCategoriesTempRepository->save($registeredCopyCategory);
            }
        }
    }

    /**
     * @param $mCorpCategoryTemp
     * @param $selectList
     * @param $listUpdateCategoryId
     */
    private function updateCorpCategoryTemp($mCorpCategoryTemp, $selectList, &$listUpdateCategoryId)
    {
        if ($this->checkUpdateWhenCorpCategoryTempCreateFromCopyRule($mCorpCategoryTemp, $selectList)
            || $this->checkUpdateWhenCorpCategoryTempCreateDefault($mCorpCategoryTemp, $selectList)
        ) {
            $mCorpCategoryTempUpdate = $this->mCorpCategoriesTempRepository->find($mCorpCategoryTemp->m_corp_categories_temp_id);
            $mCorpCategoryTempUpdate->version_no = $mCorpCategoryTempUpdate->version_no + 1;
            $mCorpCategoryTempUpdate->select_list = $selectList;
            $mCorpCategoryTempUpdate->modified_user_id = Auth::user()->user_id;
            $mCorpCategoryTempUpdate->modified = Carbon::now()->toDateTimeString();
            $this->mCorpCategoriesTempRepository->save($mCorpCategoryTempUpdate);
        }
        $listUpdateCategoryId[$mCorpCategoryTemp->category_id] = $mCorpCategoryTemp->category_id;

        $categoryCopyRuleList = $this->mCategoryCopyRuleRepository->findAllByOrgCategoryId($mCorpCategoryTemp->category_id);
        foreach ($categoryCopyRuleList as $value) {
            $listUpdateCategoryId[$value->copy_category_id] = $value->copy_category_id;
        }
    }

    /**
     * @param $mCorpCategoryTemp
     * @param $selectList
     * @return bool
     */
    private function checkUpdateWhenCorpCategoryTempCreateFromCopyRule($mCorpCategoryTemp, $selectList)
    {
        if (!is_null($selectList) && $mCorpCategoryTemp->select_list == null) {
            return true;
        }
        return false;
    }

    /**
     * @param $mCorpCategoryTemp
     * @param $selectList
     * @return bool
     */
    private function checkUpdateWhenCorpCategoryTempCreateDefault($mCorpCategoryTemp, $selectList)
    {
        if (!is_null($selectList) && $mCorpCategoryTemp->select_list != $selectList) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getListGenreNote()
    {
        $genreNoteArray = [];
        array_push($genreNoteArray, ['genreName' => 'OA機器修理', 'genreNameHref' => '#genre_key_550']);
        array_push($genreNoteArray, ['genreName' => 'エアコン', 'genreNameHref' => '#genre_key_501']);
        array_push($genreNoteArray, ['genreName' => '特殊清掃', 'genreNameHref' => '#genre_key_511']);
        array_push($genreNoteArray, ['genreName' => '電気工事', 'genreNameHref' => '#genre_key_518']);
        array_push($genreNoteArray, ['genreName' => '自動車板金', 'genreNameHref' => '#genre_key_576']);
        array_push($genreNoteArray, ['genreName' => 'その他リフォーム', 'genreNameHref' => '#genre_key_564']);
        array_push($genreNoteArray, ['genreName' => '自動ドア', 'genreNameHref' => '#genre_key_553']);
        array_push($genreNoteArray, ['genreName' => '井戸掘り', 'genreNameHref' => '#genre_key_520']);
        array_push($genreNoteArray, ['genreName' => 'ガードマン', 'genreNameHref' => '#genre_key_513']);
        array_push($genreNoteArray, ['genreName' => 'iPhone修理', 'genreNameHref' => '#genre_key_575']);
        array_push($genreNoteArray, ['genreName' => 'おしぼり', 'genreNameHref' => '#genre_key_574']);
        array_push($genreNoteArray, ['genreName' => '漏電修理', 'genreNameHref' => '#genre_key_706']);
        return $genreNoteArray;
    }
}
