<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CService
{
    const string BASE_URL = 'http://37.77.107.236:1818/TEST/hs/api/v1';

    public function getBookStaff(?Carbon $datetime = null): Response
    {
        if (!$datetime) {
            $datetime = now();
        }

        return Http::withHeaders([
            'apikey' => config('services.c.key'),
        ])
            ->withBasicAuth(config('services.c.login'), config('services.c.password'))
            ->get(self::BASE_URL . '/book_staff/9a5784d1-3a01-11ef-a31f-74563c9c055c', [
                'datetime' => $datetime->setTime(0, 0)->format('Y-m-d\TH:i'),
            ]);
    }

    public function getVisits(?Carbon $startDate = null, ?Carbon $endDate = null): Response
    {
        if (!$startDate) {
            $startDate = now();
        }

        if (!$endDate) {
            $endDate = now();
        }

        return Http::withHeaders([
            'apikey' => config('services.c.key'),
        ])
            ->withBasicAuth(config('services.c.login'), config('services.c.password'))
            ->get(self::BASE_URL . '/visites/9a5784d1-3a01-11ef-a31f-74563c9c055c', [
                'start_date' => $startDate->setTime(8, 0)->format('Y-m-d\TH:i'),
                'end_date' => $endDate->setTime(20, 0)->format('Y-m-d\TH:i'),
            ]);
    }
}
