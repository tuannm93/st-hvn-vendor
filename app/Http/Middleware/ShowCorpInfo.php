<?php

namespace App\Http\Middleware;

use App\Repositories\MCorpRepositoryInterface;
use Closure;
use Illuminate\Support\Facades\Auth;

class ShowCorpInfo
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;

    /**
     * ShowCorpInfo constructor.
     *
     * @param MCorpRepositoryInterface $mCorpRepository
     */
    public function __construct(MCorpRepositoryInterface $mCorpRepository)
    {
        $this->mCorpRepository = $mCorpRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $mCorp = $this->mCorpRepository->getFirstById(Auth::user()->affiliation_id);
        view()->share('corpId', $mCorp->id);
        view()->share('officialCorpName', $mCorp->official_corp_name);
        view()->share('typeProject', 'agreement');
        $request->attributes->add(['mCorp' => $mCorp]);
        return $next($request);
    }
}
