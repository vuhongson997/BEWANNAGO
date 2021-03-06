<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Facades\DB;

class Reservations extends AbstractWidget
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
            $count = DB::table('t_stay')
                ->join('t_reservation_detail', 't_reservation_detail.stay_id', '=', 't_stay.stay_id')
                ->join('t_reservations', 't_reservations.reservation_id', '=', 't_reservation_detail.reservation_id')
                ->where('t_stay.host_id', '=', Auth::user()->id)
                ->count();
        }else{
            $count = \App\TReservation::count();
        }
        $string = "Đặt phòng ";

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-edit',
            'title'  => "<span style='font-weight:bold'>{$string}</span><hr><span style='font-weight:bold;'>{$count}</span>",
            'text'   => "Nhấn vào nút bên dưới để xem tất cả dữ liệu.",
            'button' => [
                'text' => 'Xem thêm',
                'link' => route('voyager.t-reservations.index'),
            ],
            'image' => '/dashboard/reservations.jpg',
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
