<?php

namespace App\Console\Commands;

use App\Services\Affiliation\AffiliationAreaStatService;
use App\Services\Affiliation\AffiliationInfoService;
use App\Services\Affiliation\AffiliationStatService;
use Illuminate\Console\Command;
use App\Services\Log\ShellLogService;

class CalculateCommission extends Command
{
    /**
     * @var AffiliationStatService
     */
    protected $affiliationStatService;

    /**
     * @var AffiliationInfoService
     */
    protected $affiliationInfoService;

    /**
     * @var AffiliationAreaStatService
     */
    protected $affiliationAreaStatService;

    /**
     * @var ShellLogService
     */
    protected $shellLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:calculate_commission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取次平均単価計算処理';

    /**
     * Create a new command instance.
     *
     * @param \App\Services\Log\ShellLogService                    $shellLog
     * @param \App\Services\Affiliation\AffiliationStatService     $affiliationStatService
     * @param \App\Services\Affiliation\AffiliationInfoService     $affiliationInfoService
     * @param \App\Services\Affiliation\AffiliationAreaStatService $affiliationAreaStatService
     */
    public function __construct(
        ShellLogService $shellLog,
        AffiliationStatService $affiliationStatService,
        AffiliationInfoService $affiliationInfoService,
        AffiliationAreaStatService $affiliationAreaStatService
    ) {
        parent::__construct();
        $this->shellLog = $shellLog;
        $this->affiliationStatService = $affiliationStatService;
        $this->affiliationInfoService = $affiliationInfoService;
        $this->affiliationAreaStatService = $affiliationAreaStatService;
    }


    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $this->shellLog->log('取次平均単価計算シェルstart');
            $this->shellLog->log('加盟店カテゴリ別統計情報テーブルに未登録のカテゴリデータを作成');
            $this->affiliationStatService->setCreateAffiliationStatData();
            $this->shellLog->log('加盟店カテゴリ別統計情報テーブルに未登録のカテゴリデータを作成終了');
            $this->shellLog->log('ジャンル別取次数更新');
            $this->affiliationStatService->setCommissionGroupCategoryCount();
            $this->shellLog->log('ジャンル別取次数更新終了');
            $this->shellLog->log('ジャンル別受注数更新');
            $this->affiliationStatService->setCommissionGroupCategoryOrderCount();
            $this->shellLog->log('ジャンル別受注数更新終了');
            $this->shellLog->log('平均取次単価更新');
            $this->affiliationStatService->setCommissionInfo();
            $this->shellLog->log('平均取次単価更新終了');
            $this->shellLog->log('加盟店情報の取次件数更新');
            $this->affiliationInfoService->setCommissionCountOfAffiliation();
            $this->shellLog->log('加盟店情報の取次件数更新終了');
            $this->shellLog->log('加盟店情報の取次件数(1週間)更新');
            $this->affiliationInfoService->setCommissionWeekCountOfAffiliation();
            $this->shellLog->log('加盟店情報の取次件数(1週間)更新終了');
            $this->shellLog->log('加盟店情報の施工単価と受注数更新');
            $this->affiliationInfoService->setReceiptCount();
            $this->shellLog->log('加盟店情報の施工単価と受注数更新終了');
            $this->shellLog->log('加盟店情報の取次単価更新');
            $this->affiliationInfoService->setCommissionPrice();
            $this->shellLog->log('加盟店情報の取次単価更新');
            $this->shellLog->log('加盟店情報の受注率更新');
            $this->affiliationInfoService->setReceiptRate();
            $this->shellLog->log('加盟店情報の受注率更新');
            $this->shellLog->log('加盟店カテゴリ都道府県別統計情報テーブルに未登録のカテゴリデータを作成');
            $this->affiliationAreaStatService->setCreateAffiliationAreaStatData();
            $this->shellLog->log('加盟店カテゴリ都道府県別統計情報テーブルに未登録のカテゴリデータを作成終了');
            $this->shellLog->log('都道府県ジャンル別取次数更新');
            $this->affiliationAreaStatService->setAreaCommissionGroupCategoryCount();
            $this->shellLog->log('都道府県ジャンル別取次数更新終了');
            $this->shellLog->log('都道府県別平均取次単価更新');
            $this->affiliationAreaStatService->setAreaCommissionInfo();
            $this->shellLog->log('都道府県平均取次単価更新終了');
            $this->shellLog->log('取次平均単価計算シェルend');
        } catch (\Exception $exception) {
            $this->shellLog->log($exception);
        }
    }
}
