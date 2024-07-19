<?php

declare(strict_types=1);

namespace NMSTracker\PlanetPOIs;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use Application_Formable_RecordSettings_ValueSet;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_InputText;
use NMSTracker;
use NMSTracker\PlanetPOIsCollection;
use NMSTracker\Planets\PlanetRecord;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 */
class PlanetPOISettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    public const SETTING_LONGITUDE = 'longitude';
    public const SETTING_LATITUDE = 'latitude';
    private PlanetRecord $planet;

    public function __construct(Application_Formable $formable, PlanetPOIsCollection $collection, PlanetRecord $planet, ?PlanetPOIRecord $record = null)
    {
        $this->planet = $planet;
        
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
            ->setIcon(NMSTracker::icon()->settings())
            ->expand();

        $group->registerSetting(PlanetPOIsCollection::COL_PLANET_ID)
            ->makeVirtual($this->planet->getID());

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(PlanetPOIsCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting('comments')
            ->setStorageName(PlanetPOIsCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));

        $group = $this->addGroup(t('Coordinates'))
            ->setIcon(NMSTracker::icon()->coordinates())
            ->expand();

        $group->registerSetting(self::SETTING_LONGITUDE)
            ->setStorageName(PlanetPOIsCollection::COL_COORDINATE_LONGITUDE)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLongitude')),
                array($this, 'injectLongitude')
            ));

        $group->registerSetting(self::SETTING_LATITUDE)
            ->setStorageName(PlanetPOIsCollection::COL_COORDINATE_LATITUDE)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLatitude')),
                array($this, 'injectLatitude')
            ));
    }

    private function injectComments(Application_Formable_RecordSettings_Setting $setting) : \HTML_QuickForm2_Element_Textarea
    {
        $el = $this->addElementTextarea($setting->getName(), t('Comments'));
        $el->addFilterTrim();
        $el->addClass('input-xxlarge');
        $el->setRows(3);

        $this->addRuleNoHTML($el);

        return $el;
    }

    private function injectLabel(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $el = $this->addElementText($setting->getName(), t('Label'));
        $el->addFilterTrim();
        $el->addClass('input-xxlarge');

        $this->addRuleNameOrTitle($el);

        return $el;
    }

    private function injectLongitude(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $el = $this->addElementText($setting->getName(), t('Longitude'));
        $el->addFilterTrim();
        $el->addClass('input-small');

        $this->addRuleFloat($el, -9000);

        return $el;
    }

    private function injectLatitude(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $el = $this->addElementText($setting->getName(), t('Latitude'));
        $el->addFilterTrim();
        $el->addClass('input-small');

        $this->addRuleFloat($el, -9000);

        return $el;
    }

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canEditPOIs();
    }
}