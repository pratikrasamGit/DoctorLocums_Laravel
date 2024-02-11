<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Role extends Enum
{
	const FULLADMIN = 0;
	const ADMIN = 1;
	const FACILITY = 2;
	const NURSE = 3;
	const FACILITYADMIN = 4;
}
