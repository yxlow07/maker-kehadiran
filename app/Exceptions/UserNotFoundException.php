<?php

namespace app\Exceptions;

use core\Exceptions\BaseException;

class UserNotFoundException extends BaseException
{
    protected $message = "User is not found or method is not allowed";
    protected $code = 404;
}