<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Date;

class GenerateDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:generate-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts dates in configured interval';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $startDate = new \DateTime(env('START_DATE', '2017-01-01'));
        $endDate = new \DateTime(env('DATES_END', '2020-01-01'));
        $interval = new \DateInterval('P1M');

        $dates = new \DatePeriod($startDate, $interval, $endDate);

        $datesArray = [];

        foreach ($dates as $date) {
            $datesArray[] = ['month' => $date->format('m'), 'year' => $date->format('Y')];
        }

        Date::query()->delete();
        $inserted = Date::query()->insert($datesArray);

        if ($inserted) {
            print_r('Dates table refilled with dates between ' . env('START_DATE', '2017-01-01') . ' and ' . env('DATES_END', '2020-01-01'));
        } else {
            print_r('Could not fill dates table.');
        }
    }
}
