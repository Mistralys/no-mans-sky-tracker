<?php

declare(strict_types=1);

namespace NMSTracker\Area\TagsScreen;

use Application_Admin_Area_Mode_CollectionList;
use AppUtils\ClassHelper;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\Tags\TagFilterCriteria;
use NMSTracker\Tags\TagFilterSettings;
use NMSTracker\Tags\TagRecord;
use NMSTracker\TagsCollection;
use NMSTracker_User;
use UI;

/**
 * @property TagFilterCriteria $filters
 * @property TagFilterSettings $filterSettings
 * @property NMSTracker_User $user
 */
class TagsListScreen extends Application_Admin_Area_Mode_CollectionList
{
    public const URL_NAME = 'list';
    public const COL_LABEL = 'label';

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    /**
     * @return TagsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createTags();
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        $tag = ClassHelper::requireObjectInstanceOf(
            TagRecord::class,
            $record
        );

        return array(
            self::COL_LABEL => $tag->getLabelLinked(),
        );
    }

    protected function configureColumns() : void
    {
        $this->grid->addColumn(self::COL_LABEL, t('Label'))
            ->setSortable(true, TagsCollection::COL_LABEL);
    }

    protected function configureActions() : void
    {
    }

    public function getBackOrCancelURL() : string
    {
        return APP_URL;
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add_tag', t('Add a tag...'))
            ->setIcon(UI::icon()->add())
            ->makeLinked($this->createCollection()->getAdminCreateURL());

        parent::_handleSidebar();
    }

    protected function _handleHelp() : void
    {
        $this->renderer
            ->getTitle()
            ->setIcon(NMSTracker::icon()->tags());
    }

    public function isUserAllowed() : bool
    {
        return $this->user->canViewTags();
    }

    public function getNavigationTitle() : string
    {
        return t('Tags');
    }

    public function getTitle() : string
    {
        return t('Tags');
    }
}
