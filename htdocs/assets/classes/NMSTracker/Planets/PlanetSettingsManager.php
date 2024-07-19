<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use Application_Formable_RecordSettings_ValueSet;
use AppUtils\ClassHelper;
use AppUtils\NamedClosure;
use NMSTracker;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_InputText;
use HTML_QuickForm2_Element_Select;
use HTML_QuickForm2_Element_Switch;
use HTML_QuickForm2_Element_Textarea;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetsCollection;
use NMSTracker\SentinelLevelsCollection;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;
use NMSTracker_User;
use UI;

/**
 * @property PlanetRecord|NULL $record
 */
class PlanetSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_TAGS = 'tags';
    private SolarSystemRecord $solarSystem;

    public function __construct(Application_Formable $formable, DBHelper_BaseCollection $collection, SolarSystemRecord $solarSystem, ?DBHelper_BaseRecord $record = null)
    {
        $this->solarSystem = $solarSystem;

        parent::__construct($formable, $collection, $record);

        // Allow for record column name and setting manager name mismatches
        $this->setDefaultsUseStorageNames(true);
    }

    // region: Process data

    protected function _afterSave(DBHelper_BaseRecord $record, Application_Formable_RecordSettings_ValueSet $data) : void
    {
        $planet = ClassHelper::requireObjectInstanceOf(PlanetRecord::class, $record);

        $this->updateResources($planet, (array)$data->getKey(self::SETTING_RESOURCES));
        $this->updateTags($planet, (array)$data->getKey(self::SETTING_TAGS));

        if($data->getKey(self::SETTING_SCAN_COMPLETE) === 'yes') {
            $record->setRecordBooleanKey(PlanetsCollection::COL_PLANET_FALL_MADE, true);
            $record->save();
        }
    }

    protected function processPostCreateSettings(DBHelper_BaseRecord $record, Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues): void
    {
    }

    protected function getCreateData(Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues): void
    {
    }

    protected function updateRecord(Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues) : void
    {
        $planet = ClassHelper::requireObjectInstanceOf(PlanetRecord::class, $this->record);

        $this->updateResources($planet, (array)$internalValues[self::SETTING_RESOURCES]);
        $this->updateTags($planet, (array)$internalValues[self::SETTING_RESOURCES]);
    }

    /**
     * @param PlanetRecord $record
     * @param string[] $resourceIDs
     * @return void
     */
    private function updateResources(PlanetRecord $record, array $resourceIDs) : void
    {
        $record->updateResourcesFromForm($resourceIDs);
    }

    /**
     * @param PlanetRecord $record
     * @param string[] $tagIDs
     * @return void
     */
    private function updateTags(PlanetRecord $record, array $tagIDs) : void
    {
        $record->updateTagsFromForm($tagIDs);
    }

    // endregion

    // region: Register settings

    public const SETTING_IS_MOON = 'is_moon';
    public const SETTING_OWN_DISCOVERY = 'own_discovery';
    public const SETTING_PLANETFALL = 'planetfall';
    public const SETTING_AMOUNT_FAUNA = 'amount_fauna';
    public const SETTING_RATING = 'rating';
    public const SETTING_SENTINELS = 'sentinels';
    public const SETTING_RESOURCES = 'resources';
    public const SETTING_COMMENTS = 'comments';
    public const SETTING_SCAN_COMPLETE = 'scan_complete';
    public const SETTING_LABEL = 'label';
    public const SETTING_TYPE = 'type';

    protected function registerSettings() : void
    {
        $group = $this->addGroup(t('Settings'))
            ->expand()
            ->setIcon(UI::icon()->settings());

        $group->registerSetting(PlanetsCollection::COL_SOLAR_SYSTEM_ID)
            ->makeVirtual($this->solarSystem->getID());

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(PlanetsCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting(self::SETTING_TYPE)
            ->setStorageName(PlanetsCollection::COL_PLANET_TYPE_ID)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectType')),
                array($this, 'injectType')
            ));

        $group->registerSetting(self::SETTING_IS_MOON)
            ->setStorageName(PlanetsCollection::COL_IS_MOON)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectIsMoon')),
                array($this, 'injectIsMoon')
            ));

        $group->registerSetting(self::SETTING_SENTINELS)
            ->setStorageName(PlanetsCollection::COL_SENTINEL_LEVEL_ID)
            ->setDefaultValue(SentinelLevelsCollection::ID_NONE)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectSentinels')),
                array($this, 'injectSentinels')
            ));

        $group->registerSetting(self::SETTING_RATING)
            ->setStorageName(PlanetsCollection::COL_RATING)
            ->setDefaultValue(PlanetRatings::DEFAULT_RATING)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectRating')),
                array($this, 'injectRating')
            ));

        $group = $this->addGroup(t('Discoveries'))
            ->setIcon(NMSTracker::icon()->discoveries());

        $group->registerSetting(self::SETTING_OWN_DISCOVERY)
            ->setStorageName(SolarSystemsCollection::COL_IS_OWN_DISCOVERY)
            ->setDefaultValue('yes')
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectOwnDiscovery')),
                array($this, 'injectOwnDiscovery')
            ));

        $group->registerSetting(self::SETTING_AMOUNT_FAUNA)
            ->setStorageName(PlanetsCollection::COL_FAUNA_AMOUNT)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectFaunaAmount')),
                array($this, 'injectFaunaAmount')
            ));

        $group->registerSetting(self::SETTING_SCAN_COMPLETE)
            ->setStorageName(PlanetsCollection::COL_SCAN_COMPLETE)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectScanComplete')),
                array($this, 'injectScanComplete')
            ));

        $group->registerSetting(self::SETTING_PLANETFALL)
            ->setStorageName(PlanetsCollection::COL_PLANET_FALL_MADE)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectPlanetFallMade')),
                array($this, 'injectPlanetFallMade')
            ));

        $group = $this->addGroup(t('Comments'))
            ->setIcon(UI::icon()->options());

        $group->registerSetting(self::SETTING_COMMENTS)
            ->setStorageName(PlanetsCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));

        $suffix = '';
        if(isset($this->record)) {
            $suffix = ' '.sb()->muted(sb()->parentheses($this->record->countTags()));
        }

        $group = $this->addGroup(t('Tags').$suffix)
            ->setIcon(NMSTracker::icon()->tags());

        $group->registerSetting(self::SETTING_TAGS)
            ->makeInternal()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectTags')),
                array($this, 'injectTags')
            ));

        $suffix = '';
        if(isset($this->record)) {
            $suffix = ' '.sb()->muted(sb()->parentheses($this->record->countResources()));
        }

        $group = $this->addGroup(t('Resources').$suffix)
            ->setIcon(UI::icon()->presets());

        $group->registerSetting(self::SETTING_RESOURCES)
            ->makeInternal()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectResources')),
                array($this, 'injectResources')
            ));
    }

    // endregion

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->can(NMSTracker_User::RIGHT_CREATE_PLANETS);
    }

    // region: Form elements

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    private function injectOwnDiscovery(Application_Formable_RecordSettings_Setting $setting) : \HTML_QuickForm2_Element_Switch
    {
        $el = $this->addElementSwitch($setting->getName(), t('Own discovery?'));
        $el->setComment(t('Is this a personal discovery, or by someone else?'));
        $el->makeYesNo();
        $el->setValues('yes', 'no');

        return $el;
    }

    private function injectLabel(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $this->addHiddenVar(SolarSystemsCollection::PRIMARY_NAME, (string)$this->solarSystem->getID());
        $this->addFormablePageVars();

        $el = $this->addElementText($setting->getName(), t('Name'));
        $el->addClass('input-xxlarge');

        $this->makeLengthLimited($el, 0, 160);

        return $el;
    }

    private function injectComments(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Textarea
    {
        $el = $this->addElementTextarea($setting->getName(), t('Comments'));
        $el->setRows(3);
        $el->addClass('input-xxlarge');

        return $el;
    }

    private function injectType(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Type'));

        $el->addOption(t('Please select...'), '');

        $types = ClassFactory::createPlanetTypes()->getAll();

        foreach($types as $type)
        {
            $el->addOption($type->getLabel(), (string)$type->getID());
        }

        return $el;
    }

    private function injectSentinels(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Sentinels'));

        $levels = ClassFactory::createSentinelLevels()->getAll();

        foreach($levels as $level)
        {
            $el->addOption($level->getLabel(), (string)$level->getID());
        }

        return $el;
    }

    private function injectFaunaAmount(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Fauna count'));

        $max = 14;
        $el->addOption(t('Not recorded'), 'not_recorded');

        for($i=0; $i <= $max; $i++) {
            $el->addOption((string)$i, (string)$i);
        }

        return $el;
    }

    private function injectScanComplete(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Switch
    {
        $el = $this->addElementSwitch($setting->getName(), t('Fauna scan complete?'));
        $el->makeYesNo();
        $el->setValues('yes', 'no');

        return $el;
    }

    private function injectPlanetFallMade(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Switch
    {
        $el = $this->addElementSwitch($setting->getName(), t('Planet-fall made?'));
        $el->makeYesNo();
        $el->setValues('yes', 'no');
        $el->setComment((string)sb()
            ->t('Whether you have landed on the planet.')
            ->note()
            ->t('Planets can be discovered without landing on them using the freighter\'s system scanner.')
        );

        return $el;
    }

    private function injectIsMoon(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Switch
    {
        $el = $this->addElementSwitch($setting->getName(), t('Is a moon?'));
        $el->makeYesNo();
        $el->setValues('yes', 'no');

        return $el;
    }

    private function injectRating(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $ratings = PlanetRatings::getInstance()->getAll();

        $el = $this->addElementSelect($setting->getName(), t('Rating'));
        $el->setAttribute('size', count($ratings));

        foreach($ratings as $rating)
        {
            $el->addOption($rating->getLabelForSelect(), $rating->getID());
        }

        return $el;
    }

    private function injectTags(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $tags = ClassFactory::createTags()->getAll();

        $el = $this->addElementSelect($setting->getName(), t('Tags'));
        $el->setAttribute('size', count($tags));
        $el->setAttribute('multiple');

        foreach($tags as $tag)
        {
            $el->addOption($tag->getLabel(), $tag->getID());
        }

        return $el;
    }

    private function injectResources(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $resources = ClassFactory::createResources()->getAll();

        $el = $this->addElementSelect($setting->getName(), t('Resources'));
        $el->setAttribute('size', count($resources));
        $el->setAttribute('multiple');

        foreach($resources as $resource)
        {
            $el->addOption($resource->getLabel(), (string)$resource->getID());
        }

        return $el;
    }

    // endregion
}
