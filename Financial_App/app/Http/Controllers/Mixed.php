<?php

namespace App\Http\Controllers;
use App\Htpp\Controllers\ReccuringController ;
use App\Htpp\Controllers\FixedController ;


use Illuminate\Http\Request;

class Mixed extends Controller
{
    public function getAllReccAndFix (){
$reccuring = [ReccuringController::class,'show'];
$fixed = [FixedController::class,'show'];

        return response()->json(
            [
                'reccuring' => $reccuring,
                'fixed' => $fixed
            ],200
        );
    }
}
