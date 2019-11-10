<?php
/**
 * Created by PhpStorm.
 * User: windows7
 * Date: 09.11.2019
 * Time: 13:12
 */

namespace App\Services;


use App\Repositories\JournalRepository;

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

        $collection = $this->repository->getStatsOverPeriod();

        return $collection;
    }
}