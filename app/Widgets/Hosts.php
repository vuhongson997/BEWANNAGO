<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use Arrilot\Widgets\AbstractWidget;

class Hosts extends AbstractWidget
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
        $count = \App\User::whereNotIn('role_id', [1, 2])->count();
        $string = "Chủ nhà ";

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-person',
            'title'  => "<span style='font-weight:bold'>{$string}</span><hr><span style='font-weight:bold;'>{$count}</span>",
            'text'   => "Nhấn vào nút bên dưới để xem tất cả dữ liệu.",
            'button' => [
                'text' => 'Xem thêm',
                'link' => route('voyager.users.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->hasRole('admin');
    }
}
