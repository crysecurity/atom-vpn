<?php

namespace Cr4sec\AtomVPN\Exceptions;

class AtomVpnException extends \Exception
{
    const CODES = [
        1002 => InvalidAccessTokenException::class
    ];
}
