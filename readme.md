# Anbu Profiler for Laravel 5

Thanks [daylerees](https://github.com/daylerees)

![Anbu Profiler](https://raw.githubusercontent.com/daylerees/anbu/master/screenshot.png)

## Installation

添加以下内容到  `composer.json` -> `require` 

    "daylerees/anbu": "~1.0@alpha"


添加以下内容到文件 `app/config/app.php`:

    Purple\Anbu\ProfilerServiceProvider::class,

执行以下命令将会自动复制资源文件到指定目录，中括号中的参数将会强制覆盖已经存在的文件

    php artisan vendor:publish --provider="Purple\Anbu\ProfilerServiceProvider" [--force]

安装结束，心情使用吧

## Timers

如需使用此功能，需要启用Facades，添加以下内容到 `app/config/app.php` 

    'Anbu' => 'Purple\Anbu\Facades\Purple',

使用方法如下

    Anbu::timers()->start('test');
    sleep(30); // Do something interesting here.
    Anbu::timers()->end('test', 'Completed doing something.');

## Debug 

如需使用此功能，使用 `ad()` 代替 `dd()` 

    ad('foo');
   
## Problems?

如何清除数据，清除数据采用  `truncate` ，将会重置自增主键

    php artisan purple:clear


## Configuration

```
return [
    /**
     * 启用分析工具
     */
    'disable' => env('PURPLE_ENABLE', false),

    /**
     * 页面是否显示按钮
     */
    'display' => true,

    /**
     * 数据收集驱动类型
     */
    'repository' => 'Purple\Anbu\Repositories\DatabaseRepository',

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
```