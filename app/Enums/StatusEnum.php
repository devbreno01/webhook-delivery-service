<?php 

namespace App\Enums;

enum StatusEnum: string
{
    case PENDING = 'pending';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';
}