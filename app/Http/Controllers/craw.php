<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;
class craw extends Controller
{
    public function index()
    {
        $domain = "https://travel.com.vn/du-lich-ha-noi.aspx";
        $tag = "#group_id";
        $dom = HtmlDomParser::file_get_html( $domain );
        $elems = $dom->find('.noiden #group_idFilter');
    
        foreach($elems as $item){
            dump($item->option);
        }
        // dump($elems);

    }
}
