<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This file holds the list of possible faults
 */

return [
    1  => 'UNKNOWN_METHOD',
    2  => 'INCORRECT_PARAMETERS',
    3  => 'BAD_ARGUMENTS',
    4  => 'SIGNATURE_UNSUPPORTED',
    6  => 'SHUTDOWN_STATE',
    10 => 'BAD_NAME',
    11 => 'BAD_SIGNAL',
    20 => 'NO_FILE',
    21 => 'NOT_EXECUTABLE',
    30 => 'FAILED',
    40 => 'ABNORMAL_TERMINATION',
    50 => 'SPAWN_ERROR',
    60 => 'ALREADY_STARTED',
    70 => 'NOT_RUNNING',
    80 => 'SUCCESS',
    90 => 'ALREADY_ADDED',
    91 => 'STILL_RUNNING',
    92 => 'CANT_REREAD',
];
