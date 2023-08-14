<?php

declare(strict_types=1);

namespace Adedaramola\Zap\Enums;

enum Method: string
{
    case GET = 'GET';
    case PUT = 'PUT';
    case POST = 'POST';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
}
