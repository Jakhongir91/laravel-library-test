<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stats\GetStatsRequest;
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
     * @param GetStatsRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(GetStatsRequest $request)
    {
        try {
            $stats = $this->service->getStats($request->input('start_date'), $request->input('end_date'));

            return StatsCollection::make($stats);
        } catch (\Exception $exception) {
            return response()->json(['Error' => 'Check server logs'])->setStatusCode(500);
        }
    }
}
