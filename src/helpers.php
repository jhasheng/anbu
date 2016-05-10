<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16/5/10
 * Time: 13:48
 */
// Check to ensure function does not collide.
if(!function_exists('ad')) {
    // Define debug helper.
    function ad($value) {
        // Pass to anbu container instance.
        app(\Purple\Anbu\Purple::class)->getModule('debug')->debug($value);
    }
}