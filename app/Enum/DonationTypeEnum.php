<?php

namespace App\Enum;

enum DonationTypeEnum : string
{
    case ONE_TIME = 'One Time';
    case MONTHLY_RECURRING = 'Monthly Recurring';
    case QUARTERLY_RECURRING = 'Quarterly Recurring';
    case ANNUALLY_RECURRING = 'Annually Recurring';
}