<?php

namespace App\Enum;

enum OrderStatus: string
{
    case In_PREPARATION = 'in_preparation';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';
}