<?php
/**
 * Created by PhpStorm.
 * User: Krasen
 * Date: 16/5/13
 * Time: 13:42
 * Email: jhasheng@hotmail.com
 */

namespace Purple\Anbu\Exception;


class InvalidAdapterException extends \RuntimeException
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'This adapter does not support yet!';
}