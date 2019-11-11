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
    private $filterStartDate;
    private $filterEndDate;

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

        $this->filterStartDate = $startDate;
        $this->filterEndDate = $endDate;

        $array = $this->repository->getStatsOverPeriod();

        return $this->prepareStats($array);
    }

    private function prepareStats(array $array)
    {
        $stats = [];

        $monthTotal = 0;
        $yearTotal = 0;
        $total = 0;
        $monthIndex = null;
        $yearIndex = null;
        $lastIndex = count($array) - 1;

        foreach ($array as $index => $item) {
            $total += $item->value;

            if (!isset($monthIndex)) {
                $monthIndex = $item->date;
            }

            if (!isset($yearIndex)) {
                $yearIndex = $item->year;
            }

            if ($monthIndex != $item->date) {
                if ($this->isMonthInRange($monthIndex)) {
                    $stats[] = ['date' => $monthIndex, 'value' => $monthTotal];
                }

                $monthIndex = $item->date;
                $monthTotal = 0;
            }

            if ($yearIndex != $item->year) {
                if ($this->isYearInRange($yearIndex)) {
                    $stats[] = ['date' => strval($yearIndex), 'value' => $yearTotal];
                }

                $yearIndex = $item->year;
                $yearTotal = 0;
            }

            if ($this->isMonthInRange($item->date)) {
                $stats[] = ['date' => $item->date, 'title' => $item->title, 'value' => $item->value];
            }

            if ($index == $lastIndex) {
                if ($this->isMonthInRange($monthIndex)) {
                    $stats[] = ['date' => $monthIndex, 'value' => $monthTotal];
                }

                $monthIndex = $item->date;
                $monthTotal = 0;
            }

            if ($index == $lastIndex) {
                if ($this->isYearInRange($yearIndex)) {
                    $stats[] = ['date' => strval($yearIndex), 'value' => $yearTotal];
                }

                $yearIndex = $item->year;
                $yearTotal = 0;
            }

            $monthTotal += $item->value;
            $yearTotal += $item->value;
        }

        $stats[] = ['value' => $total];

        return collect($stats);
    }

    /**
     * Check if date is in given range.
     *
     * @param string $date
     * @return bool
     */
    private function isMonthInRange(string $date)
    {
        if (!$this->filterStartDate && !$this->filterEndDate) {
            return true;
        }

        if ($this->filterStartDate && $date < $this->filterStartDate->format('Y-m')) {
            return false;
        }

        if ($this->filterEndDate && $date > $this->filterEndDate->format('Y-m')) {
            return false;
        }

        return true;
    }

    /**
     * Check if date is in given range.
     *
     * @param string $year
     * @return bool
     */
    private function isYearInRange(string $year)
    {
        if (!$this->filterStartDate && !$this->filterEndDate) {
            return true;
        }

        if ($this->filterStartDate && $year < $this->filterStartDate->format('Y')) {
            return false;
        }

        if ($this->filterEndDate && $year > $this->filterEndDate->format('Y')) {
            return false;
        }

        return true;
    }
}