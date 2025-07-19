<?php

namespace App\Http\Controllers;

use App\Services\CService;
use App\Services\HomeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __construct(
        protected CService    $CService,
        protected HomeService $homeService,
    ) {}

    public function index(Request $request): Response
    {
        $date = Carbon::parse($request->get('date') ?? now());

        $startTime = '08:00';
        $endTime = '20:00';
        $partsOfHour = 4;
        $increaseInMinutes = 60 / $partsOfHour;
        $multiplier = 3;
        $minutesInOneSlot = 5;
        $slotsInOneHour = 60 / $minutesInOneSlot;

        $timeIntervals = $this->homeService->getTimeIntervals($startTime, $endTime);

        $visitsResponse = $this->CService->getVisits($date, $date)->json()['Parameters'] ?? [];
        $bookStaffResponse = $this->CService->getBookStaff($date)->json()['Parameters'] ?? [];
        $bookStaff = $this->homeService->getBookStaffData(
            $bookStaffResponse,
            $visitsResponse,
            $date,
            $startTime,
            $multiplier,
        );

        return Inertia::render('Home', compact([
            'timeIntervals',
            'partsOfHour',
            'increaseInMinutes',
            'slotsInOneHour',
            'bookStaff',
        ]));
    }
}
