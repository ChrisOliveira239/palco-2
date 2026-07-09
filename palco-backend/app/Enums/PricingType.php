<?php

namespace App\Enums;

enum PricingType: string
{
    case Free = 'free';
    case Fixed = 'fixed';
}