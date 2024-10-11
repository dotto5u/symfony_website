<?php

namespace App\Enum;

enum ProductStatus: string
{
    case Available = 'AVAILABLE';
    case SoldOut = 'SOLD_OUT';
    case PreOrder = 'PRE_ORDER';
}