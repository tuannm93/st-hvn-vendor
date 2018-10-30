<?php

namespace App\Http\Controllers\MPost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MAddress1RepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Services\MPostService;

class MPostController extends Controller
{
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepository;
    /**
     * @var MAddress1RepositoryInterface
     */
    protected $mAddress1Repository;
    /**
     * @var MPostService
     */
    protected $mPostService;

    /**
     * MPostController constructor.
     *
     * @param MPostRepositoryInterface     $mPostRepository
     * @param MAddress1RepositoryInterface $mAddress1Repository
     * @param MPostService                 $mPostService
     */
    public function __construct(
        MPostRepositoryInterface $mPostRepository,
        MAddress1RepositoryInterface $mAddress1Repository,
        MPostService $mPostService
    ) {
        parent::__construct();
        $this->mPostRepository = $mPostRepository;
        $this->mAddress1Repository = $mAddress1Repository;
        $this->mPostService = $mPostService;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function postSearchZipCode(Request $request)
    {
        $zipCode = $request->input('postCode');

        $address = $this->mPostService->getSearchZipCode($zipCode);

        return $address;
    }
}
