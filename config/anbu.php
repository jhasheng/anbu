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
    'disable'        => env('PURPLE_ENABLE', true),

    /**
     * 页面是否显示按钮
     */
    'display'        => true,

    /**
     * 数据收集驱动类型
     */
    'adapter'        => 'mysql',

    /**
     * 分析工具列表
     */
    'modules'        => [
        'dashboard', 'routes', 'request', 'query', 'logger', 'events', 'debug', 'timer', 
        'info', 'history', 'container'
//        'Anbu\Modules\Container\Container',
    ],

    /**
     * 默认模块
     */
    'default_module' => 'dashboard',

    /**
     * 路由前缀
     */
    'route_prefix'   => 'anbu',

    /**
     * 存储名（mysql:表名，redis：key值....）
     */
    'storage_name'   => 'anbu',

];