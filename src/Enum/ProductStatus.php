<?php

namespace App\Enum;

enum ProductStatus: string
{
    case Available = 'available';
    case SoldOut = 'sold_out';
    case PreOrder = 'pre_order';
}
