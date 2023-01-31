<?php

declare(strict_types=1);

namespace NMSTracker\Clusters;

use Application_Formable;
use Application_Formable_RecordSettings_Extended;
use Application_Formable_RecordSettings_Setting;
use AppUtils\NamedClosure;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseRecord;
use HTML_QuickForm2_Element_InputText;
use NMSTracker;
use NMSTracker\ClustersCollection;
use NMSTracker_User;

/**
 * @method NMSTracker_User getUser()
 */
class ClusterSettingsManager extends Application_Formable_RecordSettings_Extended
{
    public const SETTING_LABEL = 'label';
    public const SETTING_DISTANCE_TO_CORE = 'distance_to_core';
    public const SETTING_COMMENTS = 'comments';

    public function __construct(Application_Formable $formable, ClustersCollection $collection, ?ClusterRecord $record = null)
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
            ->setIcon(NMSTracker::icon()->settings());

        $group->registerSetting(self::SETTING_LABEL)
            ->setStorageName(ClustersCollection::COL_LABEL)
            ->makeRequired()
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectLabel')),
                array($this, 'injectLabel')
            ));

        $group->registerSetting(self::SETTING_DISTANCE_TO_CORE)
            ->setStorageName(ClustersCollection::COL_CORE_DISTANCE)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectCoreDistance')),
                array($this, 'injectCoreDistance')
            ));

        $group->registerSetting(self::SETTING_COMMENTS)
            ->setStorageName(ClustersCollection::COL_COMMENTS)
            ->setCallback(NamedClosure::fromClosure(
                Closure::fromCallable(array($this, 'injectComments')),
                array($this, 'injectComments')
            ));
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

    private function injectCoreDistance(Application_Formable_RecordSettings_Setting $setting) : HTML_QuickForm2_Element_InputText
    {
        $el = $this->addElementInteger($setting->getName(), t('Core distance'));
        $el->setComment((string)sb()
            ->t('The distance to the galaxy core from this cluster.')
            ->t('This can help to locate the cluster in the voyage to the core.')
            ->t('It is especially useful when using wormholes or gates for big steps forward.')
        );

        $this->setElementAppend($el, t('LY'));

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

    public function getDefaultSettingName() : string
    {
        return self::SETTING_LABEL;
    }

    public function isUserAllowedEditing() : bool
    {
        return $this->getUser()->canEditClusters();
    }
}
