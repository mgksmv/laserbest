<?php

namespace App\Http\Controllers;

use App\Libs\CRest;
use Illuminate\Http\Request;

class InstallController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        dump($data);

        $placementOptions = json_decode($data['PLACEMENT_OPTIONS'], true);
        $dealId = $placementOptions['ID'];

        $result = CRest::call(
            'crm.deal.get',
            [
                'ID' => $dealId,
            ],
        );

        dump($result);

        die;
    }
}
