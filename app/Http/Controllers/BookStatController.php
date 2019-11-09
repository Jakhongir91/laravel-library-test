<?php

namespace App\Http\Controllers;

use App\Http\Resources\Stats\StatsCollection;
use App\Services\JournalStatService;
use Illuminate\Http\Request;

class BookStatController extends Controller
{
    /**
     * @var \App\Services\JournalStatService
     */
    protected $service;

    /**
     * BookStatController constructor.
     *
     * @param \App\Services\JournalStatService $service
     */
    public function __construct(JournalStatService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        $stats = $this->service->getStats();

//        dd($stats);

        return StatsCollection::make($stats);
    }
}
