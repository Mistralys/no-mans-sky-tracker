<?php

declare(strict_types=1);

namespace NMSTracker\Outposts;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use Application_Formable_RecordSettings_ValueSet;
use AppUtils\ClassHelper;
use AppUtils\NamedClosure;
use NMSTracker\Outposts\OutpostRecord;
use NMSTracker\Planets\PlanetRecord;
use Closure;
use DBHelper;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_ExpandableSelect;
use HTML_QuickForm2_Element_InputText;
use HTML_QuickForm2_Element_Select;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\OutpostsCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 * @property OutpostRecord|NULL $record
 */
class OutpostSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    public const SETTING_ROLE = 'role';
    public const SETTING_SERVICES = 'services';
    public const SETTING_COMMENTS = 'comments';
    public const SETTING_LONGITUDE = 'longitude';
    public const SETTING_LATITUDE = 'latitude';

    private PlanetRecord $planet;

    public function __construct(Application_Formable $formable, OutpostsCollection $collection, PlanetRecord $planet, ?OutpostRecord $record = null)
    {
        $this->planet = $planet;

        parent::__construct($formable, $collection, $record);

        $this->setDefaultsUseStorageNames(true);
    }

    protected function processPostCreateSettings(DBHelper_BaseRecord $record, Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues) : void
    {
    }

    protected function getCreateData(Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues) : void
    {
    }

    protected function updateRecord(Application_Formable_RecordSettings_ValueSet $recordData, Application_Formable_RecordSettings_ValueSet $internalValues) : void
    {
        ClassHelper::requireObjectInstanceOf(OutpostRecord::class, $this->record)
            ->updateServicesFromForm((array)$internalValues[self::SETTING_SERVICES]);
    }

    protected function _afterSave(DBHelper_BaseRecord $record, Application_Formable_RecordSettings_ValueSet $data) : void
    {
        ClassHelper::requireObjectInstanceOf(OutpostRecord::class, $record)
            ->updateServicesFromForm((array)$data->getKey(self::SETTING_SERVICES));
    }

    protected function registerSettings() : void
    {
        $group = $this->addGroup(t('Settings'))
            ->setIcon(NMSTracker::icon()->settings())
            ->expand();

        $group->registerSetting(PlanetsCollection::PRIMARY_NAME)
            ->makeVirtual($this->planet->getID());

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(OutpostsCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting(self::SETTING_ROLE)
            ->setStorageName(OutpostsCollection::COL_ROLE_ID)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectRole')),
                array($this, 'injectRole')
            ));

        $group = $this->addGroup(t('Services'))
            ->setIcon(NMSTracker::icon()->services())
            ->expand();

        $group->registerSetting(self::SETTING_SERVICES)
            ->makeInternal()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectServices')),
                array($this, 'injectServices')
            ));

        $group = $this->addGroup(t('Comments'))
            ->setIcon(NMSTracker::icon()->comment())
            ->expand();

        $group->registerSetting(self::SETTING_COMMENTS)
            ->setStorageName(OutpostsCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));

        $group = $this->addGroup(t('Coordinates'))
            ->setIcon(NMSTracker::icon()->coordinates())
            ->setAbstract(t('These optional planetary coordinates are used to display the outpost in relation to other POIs (points of interest) on the planet, when available.'));

        $group->registerSetting(self::SETTING_LONGITUDE)
            ->setStorageName(OutpostsCollection::COL_LONGITUDE)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLongitude')),
                array($this, 'injectLongitude')
            ));

        $group->registerSetting(self::SETTING_LATITUDE)
            ->setStorageName(OutpostsCollection::COL_LATITUDE)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLatitude')),
                array($this, 'injectLatitude')
            ));
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

    private function injectComments(Application_Formable_RecordSettings_Setting $setting) : \HTML_QuickForm2_Element_Textarea
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

    private function injectRole(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_Select
    {
        $el = $this->addElementSelect($setting->getName(), t('Role'));

        $el->addOption(t('Please select...'), '');

        $items = ClassFactory::createOutpostRoles()->getAll();

        foreach($items as $item)
        {
            $el->addOption($item->getLabel(), (string)$item->getID());
        }

        return $el;
    }

    private function injectServices(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_ExpandableSelect
    {
        $el = $this->addElementExpandableSelect($setting->getName(), t('Services'));

        $items = ClassFactory::createOutpostServices()->getAll();

        foreach($items as $item)
        {
            $el->addOption($item->getLabel(), (string)$item->getID());
        }

        return $el;
    }

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canEditOutposts();
    }
}
