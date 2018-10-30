<?php

namespace App\Services;

use App\Helpers\MailHelper;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MTimeRepositoryInterface;
use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\VisitTimeRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;


use Exception;
use Illuminate\Support\Facades\DB;


use App\Mail\AuctionInAdvanceAnnounceResponsibility;
use App\Models\MItem;

class AuctionInAdvanceAnnounceService
{
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;
    /**
     * @var MTimeRepositoryInterface
     */
    protected $mTimeRepository;
    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepository;
    /**
     * @var VisitTimeRepositoryInterface
     */
    protected $visitTimeRepository;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenresRepository;
    /**
     * @var MSiteRepositoryInterface
     */
    protected $mSiteRepository;

    /**
     * AuctionInAdvanceAnnounceService constructor.
     *
     * @param DemandInfoRepositoryInterface  $demandInfoRepository
     * @param MTimeRepositoryInterface       $mTimeRepository
     * @param AuctionInfoRepositoryInterface $auctionInfoRepository
     * @param VisitTimeRepositoryInterface   $visitTimeRepository
     * @param MGenresRepositoryInterface     $mGenresRepository
     * @param MSiteRepositoryInterface       $mSiteRepository
     */
    public function __construct(
        DemandInfoRepositoryInterface $demandInfoRepository,
        MTimeRepositoryInterface $mTimeRepository,
        AuctionInfoRepositoryInterface $auctionInfoRepository,
        VisitTimeRepositoryInterface $visitTimeRepository,
        MGenresRepositoryInterface $mGenresRepository,
        MSiteRepositoryInterface $mSiteRepository
    ) {
        $this->demandInfoRepository  = $demandInfoRepository;
        $this->mTimeRepository       = $mTimeRepository;
        $this->auctionInfoRepository = $auctionInfoRepository;
        $this->visitTimeRepository   = $visitTimeRepository;
        $this->mGenresRepository     = $mGenresRepository;
        $this->mSiteRepository       = $mSiteRepository;
    }

    /**
     * excute immediately
     *
     * @return null
     */
    public function executeImmediately()
    {
        $item = $this->mTimeRepository->findByItemDetailAndItemCategory('immediately', 'send_mail');
        if (!isset($item->item_hour_date) && !isset($item->item_minute_date)) {
            return;
        }

        $hours = isset($item->item_hour_date) ? $item->item_hour_date : 0;
        $minutes =  isset($item->item_minute_date) ? $item->item_minute_date : 0;
        $minutes = $hours * 60 + $minutes;
        $baseDate = date('Y-m-d H:i', strtotime(date("Y/m/d H:i"). ' + '. $minutes .' minute'));
        $list = $this->demandInfoRepository->getDataExecuteImmediately($baseDate);
        $arrayAuctionId = [];
        foreach ($list as $item) {
            if (self::sendMail($item)) {
                $arrayAuctionId[] = $item->auction_infos_id;
            }
        }
        if ($arrayAuctionId) {
            $this->auctionInfoRepository->updateBeforePushFlag($arrayAuctionId);
        }
    }

    /**
     * excute normal
     *
     * @return null
     */
    public function executeNormal()
    {
        $item = $this->mTimeRepository->findByItemDetailAndItemCategory('normal', 'send_mail');
        if (!isset($item->item_hour_date) && !isset($item->item_minute_date)) {
            return;
        }
        $hours = isset($item->item_hour_date) ? $item->item_hour_date : 0;
        $minutes =  isset($item->item_minute_date) ? $item->item_minute_date : 0;
        $minutes = $hours * 60 + $minutes;
        $baseDate = date('Y-m-d H:i', strtotime(date("Y/m/d H:i"). ' + '. $minutes .' minute'));

        $list = $this->demandInfoRepository->getDataExecuteNormal($baseDate);
        $arrayAuctionId = [];
        foreach ($list as $item) {
            if (self::sendMail($item)) {
                $arrayAuctionId[] = $item->auction_infos_id;
            }
        }
        if ($arrayAuctionId) {
            $this->auctionInfoRepository->updateBeforePushFlag($arrayAuctionId);
        }
    }

    /**
     * send mail
     *
     * @param  object $data
     * @return boolean
     */
    public function sendMail($data)
    {
        try {
            $corpName      = $data->official_corp_name;
            $toAddressList = explode(';', $data->mailaddress_auction);
            $genreName     = $this->mGenresRepository->getGenreNameById($data->genre_id);
            $siteName      = $this->mSiteRepository->getNameById($data->site_id);
            $address1      = getDivTextJP("prefecture_div", $data->address1);
            $address2      = $data->address2;
            $address3      = maskingAddress3($data->address3);
            $address       = $address1 . $address2 . $address3;
            $tel1          = __('console.tel1');
            $tel2          = __('console.tel2');
            $bcc           = getDivText('bcc_mail', 'to_address');
            $dataMail = [
                'header'             => 'From:' . getDivText('before_auction_mail_setting', 'from_address'),
                'subject'            => sprintf(getDivText('before_auction_mail_setting', 'title'), $data->id),
                'corp_name'          => $corpName,
                'demand_id'          => $data->id,
                'site_name'          => $siteName,
                'genre_name'         => $genreName,
                'customer_name'      => $data->customer_name,
                'address'            => $address,
                'construction_class' => self::getDropText(MItem::BUILDING_TYPE, $data->construction_class),
                'tel1'               => $tel1,
                'tel2'               => $tel2,
                'contents'           => $data->contents,
                'from'               => config('rits.agreement_alert_mail_setting.from_address')
            ];
            foreach ($toAddressList as $address) {
                if (!empty($address)) {
                    MailHelper::sendMailWithBCC($address, new AuctionInAdvanceAnnounceResponsibility($dataMail), $bcc);
                }
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * get drop text
     *
     * @param  string $category
     * @param  integer $itemId
     * @return string
     */
    public static function getDropText($category, $itemId)
    {
        $item = DB::table('m_items')
            ->select('item_name')
            ->where('item_category', $category)
            ->where('item_id', $itemId)
            ->orderBy('sort_order', 'desc')
            ->first();
        if (!$item) {
            return;
        }
        return $item->item_name;
    }
}
