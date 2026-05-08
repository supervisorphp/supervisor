<?php

declare(strict_types=1);

namespace Supervisor;

interface StateInfoInterface
{
    public function getStateCode(): ServiceStates;

    public function getStateName(): string;
}
