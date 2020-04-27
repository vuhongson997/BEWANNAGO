<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use Arrilot\Widgets\AbstractWidget;

class Stays extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = 0;
        if(Auth::user()->hasRole('host')){
            $count = \App\TStay::where('host_id', Auth::user()->id)->count();
        }else{
            $count = \App\TStay::count();
        }
        $string = "Địa điểm cho thuê ";

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-home',
            'title'  => "<span style='font-weight:bold'>{$string}</span><hr><span style='font-weight:bold;'>{$count}</span>",
            'text'   => "Nhấn vào nút bên dưới để xem tất cả dữ liệu.",
            'button' => [
                'text' => 'Xem thêm',
                'link' => route('voyager.t-stay.index'),
            ],
            'image' => '/dashboard/stays.jpg',
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        if(Auth::user()->hasRole('host')){
            return Auth::user()->hasRole('host');
        }else{
            return Auth::user()->hasRole('admin');
        }
    }
}
