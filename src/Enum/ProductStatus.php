<?php

namespace App\Enum;

enum ProductStatus: string
{
    case AVAILABLE = 'available';
    case SOLD_OUT = 'sold_out';
    case PRE_ORDER = 'pre_order';
}
