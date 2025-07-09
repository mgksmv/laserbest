<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CService
{
    const string BASE_URL = 'http://37.77.107.236:1818/TEST/hs/api/v1';

    public function getBookStaff($datetime = null)
    {
        if (!$datetime) {
            $datetime = now()->format('Y-m-d\TH:i');
        }

        return Http::withHeaders([
            'apikey' => config('services.c.key'),
        ])
            ->withBasicAuth(config('services.c.login'), config('services.c.password'))
            ->get(self::BASE_URL . '/book_staff/9a5784d1-3a01-11ef-a31f-74563c9c055c', [
                'datetime' => $datetime,
            ]);
    }

    public function getVisites($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = now()->setTime(8, 0)->format('Y-m-d\TH:i');
        }

        if (!$endDate) {
            $endDate = now()->setTime(20, 0)->format('Y-m-d\TH:i');
        }

        return Http::withHeaders([
            'apikey' => config('services.c.key'),
        ])
            ->withBasicAuth(config('services.c.login'), config('services.c.password'))
            ->get(self::BASE_URL . '/visites/9a5784d1-3a01-11ef-a31f-74563c9c055c', [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
    }
}
