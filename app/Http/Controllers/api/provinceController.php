<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Province;
class provinceController extends Controller
{
    public function index(){
        $data = Province::paginate(10);
     
        $response = [
            'totalCount'        => $data->toArray()['total'],
            'result'   => $data->toArray()['data'],
               
                // 'per_page'     => $data->getPerPage(),
                // 'current_page' => $data->getCurrentPage(),
                // 'last_page'    => $data->getLastPage(),
                // 'from'         => $data->getFrom(),
                // 'to'           => $data->getTo()
            
        ];
       return response()->json($response, 200);
    }
}
