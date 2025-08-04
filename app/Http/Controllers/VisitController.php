<?php

namespace App\Http\Controllers;

use App\Services\CService;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function __construct(
        protected CService $CService,
    ) {}

    public function getModalData(Request $request)
    {
        $services = $this
            ->CService
            ->getBookServices($request->get('salonId'), $request->get('staffId'))
            ->json()['Parameters'] ?? [];

        return response()->json(compact([
            'services',
        ]));
    }
}
