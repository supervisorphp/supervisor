<?php

declare(strict_types=1);

namespace Supervisor;

final class StateInfo implements StateInfoInterface
{
    public function __construct(
        private readonly ServiceStates $stateCode,
        private readonly string $stateName,
    ) {}

    /** @param array{statecode: int, statename: string} $data */
    public static function fromGetState(array $data): self
    {
        return new self(
            stateCode: ServiceStates::from($data['statecode']),
            stateName: $data['statename'],
        );
    }

    public function getStateCode(): ServiceStates
    {
        return $this->stateCode;
    }

    public function getStateName(): string
    {
        return $this->stateName;
    }
}
