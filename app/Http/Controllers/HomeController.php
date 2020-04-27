<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://wannago.ml/api/test",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => array('id' => 'pi_1GUDA0GmBXNfqIUswsXT4Xch'),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_encode($response);
        
    }

    public function download(){
      $path = storage_path('app/public/app/app-debug.apk');
      // return $path;
      $headers = array('Content-type'=> 'application/vnd.android.package-archive');
      header('Content-type: application/vnd.android.package-archive');
      return response()->download($path, 'app-debug.apk',$headers);
    }
}
