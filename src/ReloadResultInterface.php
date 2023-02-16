<?php

declare(strict_types=1);

namespace Supervisor;

/**
 * A class documenting the results of a "reloadAndApplyConfig" operation.
 */
interface ReloadResultInterface
{
    /**
     * @return string[] Process group names that were affected by the reload (added, modified, or removed).
     */
    public function getAffected(): array;

    /**
     * @return string[] Process group names that were added in the reload.
     */
    public function getAdded(): array;

    /**
     * @return string[] Process group names that were added in the reload.
     */
    public function getModified(): array;

    /**
     * @return string[] Process group names that were removed in the reload.
     */
    public function getRemoved(): array;
}
