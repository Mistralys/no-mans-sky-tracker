<?php

declare(strict_types=1);

namespace NMSTracker\PlanetTypes;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_InputText;
use HTML_QuickForm2_Element_Textarea;
use NMSTracker;
use NMSTracker\PlanetTypesCollection;
use NMSTracker\UI\FormHelper;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 */
class PlanetTypeSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    public const SETTING_COMMENTS = 'comments';

    private FormHelper $formHelper;

    public function __construct(Application_Formable $formable, DBHelper_BaseCollection $collection, ?DBHelper_BaseRecord $record = null)
    {
        parent::__construct($formable, $collection, $record);

        $this->setDefaultsUseStorageNames(true);

        $this->formHelper = new FormHelper($this);
    }

    protected function processPostCreateSettings(DBHelper_BaseRecord $record, array $formValues) : void
    {
    }

    protected function getCreateData(array $formValues) : array
    {
        return $formValues;
    }

    protected function updateRecord(array $values) : void
    {

    }

    protected function registerSettings() : void
    {
        $group = $this->addGroup(t('Settings'))
            ->setIcon(NMSTracker::icon()->settings());

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(PlanetTypesCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting(self::SETTING_COMMENTS)
            ->setStorageName(PlanetTypesCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));
    }

    private function injectLabel(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        return $this->formHelper->injectLabel($setting, t('Label'));
    }

    private function injectComments(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Textarea
    {
        return $this->formHelper->injectComments($setting, t('Comments'));
    }

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canEditPlanetTypes();
    }
}
