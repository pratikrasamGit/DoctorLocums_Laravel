<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class WorkLocation extends Enum
{
    const LocalTraveler =   0;
    const RegionalTraveler =   1;
    const NationalTraveler = 2;
    const InternationalTraveler = 3;
}
