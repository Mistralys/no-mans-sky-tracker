<?php

declare(strict_types=1);

namespace NMSTracker\Tags;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use Application_Formable_RecordSettings_ValueSet;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_InputText;
use NMSTracker;
use NMSTracker\TagsCollection;
use NMSTracker\UI\FormHelper;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 */
class TagSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    private FormHelper $formHelper;

    public function __construct(Application_Formable $formable, TagsCollection $collection, ?TagRecord $record = null)
    {
        parent::__construct($formable, $collection, $record);

        $this->setDefaultsUseStorageNames(true);
    }

    protected function processPostCreateSettings(DBHelper_BaseRecord $record, Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues): void
    {
    }

    protected function getCreateData(Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues): void
    {
    }

    protected function updateRecord(Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues): void
    {
    }

    protected function registerSettings() : void
    {
        $this->formHelper = new FormHelper($this);

        $group = $this->addGroup(t('Settings'))
            ->setIcon(NMSTracker::icon()->settings());

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(NMSTracker\TagsCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));
    }

    private function injectLabel(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        return $this->formHelper->injectLabel($setting, t('Label'));
    }

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canEditTags();
    }
}
