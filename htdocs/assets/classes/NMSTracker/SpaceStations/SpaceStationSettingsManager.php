<?php

declare(strict_types=1);

namespace NMSTracker\SpaceStations;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use Application_Formable_RecordSettings_ValueSet;
use AppUtils\ClassHelper;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_ExpandableSelect;
use HTML_QuickForm2_Element_InputText;
use HTML_QuickForm2_Element_Select;
use HTML_QuickForm2_Element_Textarea;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\SpaceStationsCollection;
use NMSTracker_User;

/**
 * @property SpaceStationsCollection $collection
 * @property SpaceStationRecord|NULL $record
 * @method NMSTracker_User getUser()
 */
class SpaceStationSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    public const SETTING_COMMENTS = 'comments';
    public const SETTING_SELL_OFFERS = 'sell_offers';
    public const SETTING_BUY_OFFERS = 'buy_offers';
    public const SETTING_SOLAR_SYSTEM = 'solar_system';

    public function __construct(Application_Formable $formable, SpaceStationsCollection $collection, ?SpaceStationRecord $record = null)
    {
        parent::__construct($formable, $collection, $record);

        $this->setDefaultsUseStorageNames(true);
    }

    protected function processPostCreateSettings(DBHelper_BaseRecord $record, array $formValues) : void
    {
    }

    protected function _afterSave(DBHelper_BaseRecord $record, Application_Formable_RecordSettings_ValueSet $data) : void
    {
        ClassHelper::requireObjectInstanceOf(
            SpaceStationRecord::class,
            $record
        )
            ->updateTradeOffersFromForm(
                (array)$data->getKey(self::SETTING_SELL_OFFERS),
                (array)$data->getKey(self::SETTING_BUY_OFFERS)
            );
    }

    protected function getCreateData(array $formValues) : array
    {
        return $formValues;
    }

    protected function updateRecord(array $values) : void
    {
        ClassHelper::requireObjectInstanceOf(
            SpaceStationRecord::class,
            $this->record
        )
            ->updateTradeOffersFromForm(
                $values[self::SETTING_SELL_OFFERS] ?? array(),
                $values[self::SETTING_BUY_OFFERS] ?? array()
            );
    }

    protected function registerSettings() : void
    {
        $group = $this->addGroup(t('Settings'))
            ->setIcon(NMSTracker::icon()->settings())
            ->expand();

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(SpaceStationsCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting(self::SETTING_SOLAR_SYSTEM)
            ->setStorageName(SpaceStationsCollection::COL_SOLAR_SYSTEM_ID)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectSolarSystem')),
                array($this, 'injectSolarSystem')
            ));

        $group->registerSetting(self::SETTING_COMMENTS)
            ->setStorageName(SpaceStationsCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));

        $group = $this->addGroup(t('Trade commodities'))
            ->setIcon(NMSTracker::icon()->resources());

         $group->registerSetting(self::SETTING_SELL_OFFERS)
             ->makeInternal()
             ->setCallback(NamedClosure::fromClosure(
                 Closure::fromCallable(array($this, 'injectSellOffers')),
                 array($this, 'injectSellOffers')
             ));

        $group->registerSetting(self::SETTING_BUY_OFFERS)
            ->makeInternal()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectBuyOffers')),
                array($this, 'injectBuyOffers')
            ));
    }

    private function injectSolarSystem(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Solar system'));

        $systems = ClassFactory::createSolarSystems()->getAll();

        foreach($systems as $system)
        {
            $el->addOption($system->getLabel(), (string)$system->getID());
        }

        return $el;
    }

    private function injectLabel(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $this->addFormablePageVars();

        $el = $this->addElementText($setting->getName(), t('Name'));
        $el->addClass('input-xxlarge');

        $this->makeLengthLimited($el, 0, 160);

        return $el;
    }

    private function injectComments(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Textarea
    {
        $el = $this->addElementTextarea($setting->getName(), t('Comments'));
        $el->addFilterTrim();
        $el->setRows(3);
        $el->addClass('input-xxlarge');

        $this->addRuleNoHTML($el);

        return $el;
    }

    private function injectBuyOffers(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_ExpandableSelect
    {
        return $this->_injectResources(
            $setting,
            t('Buy offers')
        );
    }

    private function injectSellOffers(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_ExpandableSelect
    {
        return $this->_injectResources(
            $setting,
            t('Sell offers')
        );
    }

    private function _injectResources(Application_Formable_RecordSettings_Setting $setting, string $label) : HTML_QuickForm2_Element_ExpandableSelect
    {
        $el = $this->addElementExpandableSelect($setting->getName(), $label);

        $collection = ClassFactory::createResources();

        $resources = $collection
            ->getFilterCriteria()
            ->selectTradeCommodities()
            ->getItemsObjects();

        foreach($resources as $resource)
        {
            $el->addOption($resource->getLabel(), (string)$resource->getID());
        }

        return $el;
    }

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canEditSpaceStations();
    }
}