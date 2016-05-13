<?php

namespace Purple\Anbu\Exception;

use RuntimeException;

class InvalidModuleException extends RuntimeException
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'This module does not implement the abstract \'Module\' class.';
}
