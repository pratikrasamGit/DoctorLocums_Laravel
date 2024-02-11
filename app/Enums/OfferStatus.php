<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OfferStatus extends Enum
{
    const Pending = 'Pending';
    const Approve = 'Approve';
    const Rejected = 'Rejected';
    const Expired = 'Expired';
}
