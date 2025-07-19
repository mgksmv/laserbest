<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CService
{
    const string BASE_URL = 'http://37.77.107.236:1818/TEST/hs/api/v1';

    public function getBookStaff(string $salonId, ?Carbon $datetime = null): Response
    {
        if (!$datetime) {
            $datetime = now();
        }

        return Http::withHeaders([
            'apikey' => config('services.c.key'),
        ])
            ->withBasicAuth(config('services.c.login'), config('services.c.password'))
            ->get(self::BASE_URL . '/book_staff/' . $salonId, [
                'datetime' => $datetime->setTime(0, 0)->format('Y-m-d\TH:i'),
            ]);
    }

    public function getVisits(string $salonId, ?Carbon $startDate = null, ?Carbon $endDate = null): Response
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
            ->get(self::BASE_URL . '/visites/' . $salonId, [
                'start_date' => $startDate->setTime(8, 0)->format('Y-m-d\TH:i'),
                'end_date' => $endDate->setTime(20, 0)->format('Y-m-d\TH:i'),
            ]);
    }
}
