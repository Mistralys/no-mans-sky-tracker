<?php

declare(strict_types=1);

namespace NMSTracker;

use Application\ConfigSettings\BaseConfigRegistry;
use Application\Environments\BaseEnvironmentsConfig;
use Application\Environments\Environment;
use NMSTracker\Environments\LocalEnvironment;

class EnvironmentsConfig extends BaseEnvironmentsConfig
{
    protected function getClassName(): string
    {
        return 'NMSTracker';
    }

    protected function getCompanyName(): string
    {
        return 'Mistralys';
    }

    protected function getDummyEmail(): string
    {
        return 'info@mistralys.eu';
    }

    protected function getSystemEmail(): string
    {
        return 'nms-tracker@mistralys.com';
    }

    protected function getSystemName(): string
    {
        return 'NMS Tracker';
    }

    protected function getContentLocales(): array
    {
        return array('en_UK');
    }

    protected function getUILocales(): array
    {
        return array('en_UK');
    }

    protected function createCustomSettings(): BaseConfigRegistry
    {
        return new ConfigRegistry();
    }

    protected function configureDefaultSettings(Environment $environment): void
    {
        $this->config
            ->setURL(NMS_TRACKER_URL)
            ->setInstanceID('main')
            ->setAppSet('main')
            ->setLoggingEnabled(true)
            ->setDeeplProxyEnabled(false)
            ->setSimulateSession(true)
            ->setJavascriptMinified(false)
            ->setRequestLogPassword('reqlog')

            ->setDBHost(NMS_TRACKER_DB_HOST)
            ->setDBName(NMS_TRACKER_DB_NAME)
            ->setDBPort((int)NMS_TRACKER_DB_PORT)
            ->setDBUser(NMS_TRACKER_DB_USER)
            ->setDBPassword(NMS_TRACKER_DB_PASSWORD)

            ->setDBTestsHost(NMS_TRACKER_TEST_DB_HOST)
            ->setDBTestsName(NMS_TRACKER_TEST_DB_NAME)
            ->setDBTestsPort((int)NMS_TRACKER_TEST_DB_PORT)
            ->setDBTestsUser(NMS_TRACKER_TEST_DB_USER)
            ->setDBTestsPassword(NMS_TRACKER_TEST_DB_PASSWORD);
    }

    protected function getRequiredSettingNames(): array
    {
        return array();
    }

    public function getDefaultEnvironmentID(): string
    {
        return LocalEnvironment::ENVIRONMENT_ID;
    }

    protected function getEnvironmentClasses(): array
    {
        return array(
            LocalEnvironment::class
        );
    }

    protected function getSystemEmailRecipients(): string
    {
        return 'info@mistralys.com';
    }
}
