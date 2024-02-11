<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Specialty extends Enum
{
    const EmergencyRoom =   0;
    const MedicalSurgicalTelemetry =   1;
    const CriticalCare = 2;
    const OperatingRoom =   3;
    const PACURecovery =   4;
    const CathLabInterventionalRadiology = 5;
    const LaborDelivery =   6;
    const MotherBaby =   7;
    const NeonatalICU = 8;
    const PediatricsPediatricsICU =   9;
    const CaseManagement =   10;
    const InfectionPrevention = 11;
}
