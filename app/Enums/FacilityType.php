<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FacilityType extends Enum
{
    const AcuteCareHospital    =   0;
    const BehavioralHealthHospital =   1;
    const AmbulatoryCareFacility = 2;
    const AssistedLiving = 3;
    const SkilledNursing = 4;
    const Other = 5;
}
