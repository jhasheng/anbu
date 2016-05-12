<?php
/**
 * Created by PhpStorm.
 * User: Krasen
 * Date: 16/5/12
 * Time: 18:01
 * Email: jhasheng@hotmail.com
 */

return [
    /**
     * 启用分析工具
     */
    'disable' => env('PURPLE_ENABLE', true),

    /**
     * 页面是否显示按钮
     */
    'display' => true,

    /**
     * 数据收集驱动类型
     */
    'adapter' => 'mysql',

    /**
     * 分析工具列表
     */
    'modules' => [
        'Purple\Anbu\Modules\Dashboard\Dashboard',
        'Purple\Anbu\Modules\RoutesBrowser\RoutesBrowser',
        'Purple\Anbu\Modules\Request\Request',
        'Purple\Anbu\Modules\QueryLogger\QueryLogger',
        'Purple\Anbu\Modules\Logger\Logger',
        'Purple\Anbu\Modules\Events\Events',
        'Purple\Anbu\Modules\Debug\Debug',
        'Purple\Anbu\Modules\Timers\Timers',
        'Purple\Anbu\Modules\Info\Info',
        'Purple\Anbu\Modules\History\History',
//        'Anbu\Modules\Container\Container',
    ],
    
];