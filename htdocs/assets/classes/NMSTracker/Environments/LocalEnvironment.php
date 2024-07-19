<?php

declare(strict_types=1);

namespace NMSTracker\Environments;

use Application\Environments;
use Application\Environments\EnvironmentSetup\BaseEnvironmentConfig;

class LocalEnvironment extends BaseEnvironmentConfig
{
    public const ENVIRONMENT_ID = 'local';

    public function getID(): string
    {
        return self::ENVIRONMENT_ID;
    }

    public function getType(): string
    {
        return Environments::TYPE_DEV;
    }

    protected function configureCustomSettings(): void
    {
    }

    protected function setUpEnvironment(): void
    {
        $this->environment->includeFile($this->configFolder.'/config.php');
    }
}
