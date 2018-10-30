<?php


namespace Tests\Unit\Services\Affiliation;

use App\Services\Affiliation\AffiliationCorpService;
use Tests\TestCase;
use Mockery;

class AffiliationCorpServiceTest extends TestCase
{
    /**
     *
     *
     * @var AffiliationCorpService
     */
    private $affiliationCorpService;
    private $mCorpRepository;
    private $mTargetAreaRepository;
    private $affiliationInfoRepository;
    private $mPostRepository;
    private $mCorpNewYearRepository;
    private $mCorpSubRepository;

    public function setUp()
    {
        $this->mCorpRepository = Mockery::mock('\App\Repositories\MCorpRepositoryInterface');
        $this->mTargetAreaRepository = Mockery::mock('\App\Repositories\MTargetAreaRepositoryInterface');
        $this->affiliationInfoRepository = Mockery::mock('\App\Repositories\AffiliationInfoRepositoryInterface');
        $this->mPostRepository = Mockery::mock('\App\Repositories\MPostRepositoryInterface');
        $this->mCorpNewYearRepository = Mockery::mock('\App\Repositories\MCorpNewYearRepositoryInterface');
        $this->mCorpSubRepository = Mockery::mock('\App\Repositories\MCorpSubRepositoryInterface');

        parent::setUp(); // Change the autogenerated stub
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown(); // Change the autogenerated stub
    }

    public function testGetMCorpData()
    {
        $this->mCorpRepository
            ->shouldReceive('findByIdForAffiliation')
            ->andReturn(['callToFindByIdForAffiliation']);
        $this->affiliationCorpService = new AffiliationCorpService(
            $this->mCorpRepository,
            $this->mTargetAreaRepository,
            $this->affiliationInfoRepository,
            $this->mPostRepository,
            $this->mCorpNewYearRepository,
            $this->mCorpSubRepository
        );
        $result = $this->affiliationCorpService->getMCorpData(1);
        $this->assertEquals($result, ['callToFindByIdForAffiliation']);
    }

    public function testGetAffiliationInfo()
    {
        $this->affiliationInfoRepository
            ->shouldReceive('findAffiliationInfoByCorpId')
            ->andReturn(['callToFindAffiliationInfoByCorpId']);
        $this->affiliationCorpService = new AffiliationCorpService(
            $this->mCorpRepository,
            $this->mTargetAreaRepository,
            $this->affiliationInfoRepository,
            $this->mPostRepository,
            $this->mCorpNewYearRepository,
            $this->mCorpSubRepository
        );
        $result = $this->affiliationCorpService->getAffiliationInfo(1);
        $this->assertEquals($result, ['callToFindAffiliationInfoByCorpId']);
    }

    public function testGetPrefList()
    {
        $this->mPostRepository
            ->shouldReceive('getCorpPrefAreaCount')
            ->times(47)
            ->andReturn(1, 2);
        $this->mPostRepository
            ->shouldReceive('getPrefAreaCount')
            ->times(47)
            ->andReturn(2, 1);
        $this->affiliationCorpService = new AffiliationCorpService(
            $this->mCorpRepository,
            $this->mTargetAreaRepository,
            $this->affiliationInfoRepository,
            $this->mPostRepository,
            $this->mCorpNewYearRepository,
            $this->mCorpSubRepository
        );
        $result = $this->affiliationCorpService->getPrefList(1);
        $this->assertEquals(count($result), 47);
        $this->assertEquals($result[1]['rank'], 2);
    }
}
