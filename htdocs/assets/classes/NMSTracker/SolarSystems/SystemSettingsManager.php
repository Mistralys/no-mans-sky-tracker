<?php

declare(strict_types=1);

namespace NMSTracker\SolarSystems;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_InputText;
use HTML_QuickForm2_Element_Select;
use HTML_QuickForm2_Element_Textarea;
use NMSTracker\ClassFactory;
use NMSTracker\SolarSystemsCollection;
use UI;

/**
 * @property SolarSystemRecord|NULL $record
 */
class SystemSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    public const SETTING_RACE = 'race';
    public const SETTING_STAR = 'star';
    public const SETTING_COMMENTS = 'comments';

    public function __construct(Application_Formable $formable, DBHelper_BaseCollection $collection, ?DBHelper_BaseRecord $record = null)
    {
        parent::__construct($formable, $collection, $record);

        $this->setDefaultsUseStorageNames(true);
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
            ->setIcon(UI::icon()->settings())
            ->expand();

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(SolarSystemsCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting(self::SETTING_STAR)
            ->setStorageName(SolarSystemsCollection::COL_STAR_TYPE_ID)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectStarType')),
                array($this, 'injectStarType')
            ));

        $group->registerSetting(self::SETTING_RACE)
            ->setStorageName(SolarSystemsCollection::COL_RACE_ID)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectRace')),
                array($this, 'injectRace')
            ));

        $group->registerSetting('planets')
            ->setStorageName(SolarSystemsCollection::COL_AMOUNT_PLANETS)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectAmountPlanets')),
                array($this, 'injectAmountPlanets')
            ));

        $group = $this->addGroup(t('Comments'))
            ->setIcon(UI::icon()->comment());

        $group->registerSetting(self::SETTING_COMMENTS)
            ->setStorageName(SolarSystemsCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));
    }

    private function injectLabel(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $el = $this->addElementText($setting->getName(), t('Name'));
        $el->addClass('input-xxlarge');

        $this->makeLengthLimited($el, 0, 160);

        return $el;
    }

    private function injectComments(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Textarea
    {
        $el = $this->addElementTextarea($setting->getName(), t('Comments'));

        $el->setRows(3);

        return $el;
    }

    private function injectAmountPlanets(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $min = 0;
        if(isset($this->record)) {
            $min = $this->record->countPlanets();
        }

        $el = $this->addElementInteger($setting->getName(), t('Orbital bodies'), null, $min);
        $el->addClass('input-xxlarge');
        $el->setComment((string)sb()
            ->t('The amount of planets and moons in the system.')
            ->t('Useful to keep track of any planets you have not landed on yet to discover them.')
        );

        return $el;
    }

    private function injectRace(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Dominant race'));

        $el->addOption(t('Please select...'), '');

        $races = ClassFactory::createRaces()->getAll();

        foreach($races as $race)
        {
            $el->addOption($race->getLabel(), $race->getID());
        }

        return $el;
    }

    private function injectStarType(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Star type'));

        $el->addOption(t('Please select...'), '');

        $starTypes = ClassFactory::createStarTypes()->getAll();

        foreach($starTypes as $starType)
        {
            $el->addOption($starType->getLabel(), $starType->getID());
        }

        return $el;
    }

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canCreateSolarSystems();
    }
}
