<?php
/**
 * Created by PhpStorm.
 * User: windows7
 * Date: 09.11.2019
 * Time: 13:12
 */

namespace App\Services;


use App\Repositories\JournalRepository;
use Illuminate\Support\Collection;

class JournalStatService
{
    private $repository;

    public function __construct(JournalRepository $journalRepository)
    {
        $this->repository = $journalRepository;
    }

    /**
     * Get stats by dates from query result.
     *
     * @param string|null $startDate
     * @param $endDate
     * @return Collection
     */
    public function getStats(?string $startDate, ?string $endDate)
    {
        $startDate = \DateTime::createFromFormat('Y-m-d', $startDate);
        $endDate = \DateTime::createFromFormat('Y-m-d', $endDate);

        $array = $this->repository->getStatsOverPeriod();

        return $this->prepareStats($array);
    }

    //TODO: add filter by dates
    private function prepareStats(array $array)
    {
        $stats = [];

        $monthTotal = 0;
        $yearTotal = 0;
        $total = 0;
        $monthIndex = $array[0]->date;
        $lastIndex = count($array) - 1;

        foreach ($array as $item) {
            if ($monthIndex != $item->date) {
                $stats[] = ['date' => $monthIndex, 'value' => $monthTotal];
                $monthTotal = 0;
                $monthIndex = $item->date;
            }

            $stats[] = ['date' => $item->date, 'title' => $item->title, 'value' => $item->value];
            $monthTotal += $item->value;

            $total += $item->value;
        }

        $stats[] = ['date' => $monthIndex, 'value' => $monthTotal];
        $stats[] = ['value' => $total];

        return collect($stats);
    }
}