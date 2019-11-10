<?php
/**
 * Created by PhpStorm.
 * User: windows7
 * Date: 09.11.2019
 * Time: 18:23
 */

namespace App\Repositories;


use App\Journal;
use Illuminate\Support\Facades\DB;

class JournalRepository
{
    /**
     * Query existing books' requests by months and years.
     *
     * @return Collection
     */
    public function getStatsOverPeriod()
    {
        /*
            $countByMonthsQuery = Journal::query()
                ->select(DB::raw('MONTH(created_at) as month, book_id, count(book_id) as value, YEAR(created_at) as year'));

            $countByMonthsQuery->groupBy(DB::raw('MONTH(created_at), book_id, year'))
                ->orderBy(DB::raw('year, MONTH(created_at), book_id'));

            $countByMonths = $countByMonthsQuery->get();
        */

        $countByMonths = DB::select('SELECT dates.month, dates.year, books.id as book_id, books.title, COALESCE(stats.value,0) as value FROM `dates` CROSS JOIN books
                                        LEFT JOIN
                                        
                                        (SELECT books.id as book_id, books.title, t2.month, t2.value, t2.year
                                          FROM books
                                          LEFT JOIN
                                              (SELECT MONTH(created_at) as month, book_id, count(book_id) as value, YEAR(created_at) as year FROM journal GROUP BY month, book_id, year order by year, month, book_id)
                                          as t2 on books.id = t2.book_id)
                                        as stats ON books.id = stats.book_id and dates.month = stats.month and dates.year = stats.year');

        return collect($countByMonths);
    }
}