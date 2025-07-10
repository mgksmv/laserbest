<?php

namespace App\Http\Controllers;

use App\Services\CService;
use App\Services\HomeService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __construct(
        protected CService $CService,
        protected HomeService $homeService,
    ) {}

    public function index(): Response
    {
        $startTime = '08:00';
        $endTime = '20:00';
        $partsOfHour = 4;
        $increaseInMinutes = 60 / $partsOfHour;
        $multiplier = 3;
        $minutesInOneSlot = 5;
        $slotsInOneHour = 60 / $minutesInOneSlot;

        $timeIntervals = $this->homeService->getTimeIntervals($startTime, $endTime);

        $visits = $this->CService->getVisits()->json()['Parameters'] ?? [];
        $bookStaff = $this->CService->getBookStaff()->json()['Parameters'] ?? [];
        //dump($visits);
        //dump($bookStaff);
        //die();
        $bookStaff = $this->homeService->getBookStaffData($bookStaff, $visits, $startTime, $multiplier);

        return Inertia::render('Home', compact(
            'timeIntervals',
            'partsOfHour',
            'increaseInMinutes',
            'slotsInOneHour',
            'bookStaff',
        ));
    }
}
