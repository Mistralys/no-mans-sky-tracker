<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use Application_Formable_RecordSettings_ValueSet;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_InputText;
use HTML_QuickForm2_Element_Select;
use HTML_QuickForm2_Element_Textarea;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\ResourcesCollection;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 */
class ResourceSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    public const SETTING_TYPE = 'type';
    public const SETTING_COMMENTS = 'comments';

    public function __construct(Application_Formable $formable, ResourcesCollection $collection, ?ResourceRecord $record = null)
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
        $group = $this->addGroup(t('Settings'))
            ->setIcon(NMSTracker::icon()->settings());

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(ResourcesCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting(self::SETTING_TYPE)
            ->setStorageName(ResourcesCollection::COL_TYPE)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectType')),
                array($this, 'injectType')
            ));

        $group->registerSetting(self::SETTING_COMMENTS)
            ->setStorageName(ResourcesCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));
    }

    private function injectComments(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Textarea
    {
        $el = $this->addElementTextarea($setting->getName(), t('Comments'));
        $el->addFilterTrim();
        $el->addClass('input-xxlarge');

        $this->addRuleNoHTML($el);

        return $el;
    }

    private function injectLabel(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $el = $this->addElementText($setting->getName(), t('Name'));
        $el->addFilterTrim();
        $el->addClass('input-xxlarge');

        $this->makeLengthLimited($el, 0, 160);
        $this->addRuleNameOrTitle($el);

        return $el;
    }

    private function injectType(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Resource type'));

        $el->addOption(t('Please select...'), '');

        $types = ClassFactory::createResourceTypes()->getAll();

        foreach($types as $type)
        {
            $el->addOption($type->getLabel(), $type->getID());
        }

        return $el;
    }

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canEditResources();
    }
}
