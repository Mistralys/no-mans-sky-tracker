<?php

declare(strict_types=1);

namespace NMSTracker\Tags;

use Application_Admin_ScreenInterface;
use Application_Request;
use DBHelper_BaseRecord;
use NMSTracker\Area\TagsScreen;
use NMSTracker\Area\TagsScreen\TagScreen;
use NMSTracker\Area\TagsScreen\TagScreen\TagPlanetsScreen;
use NMSTracker\Area\TagsScreen\TagScreen\TagSettingsScreen;
use NMSTracker\TagsCollection;
use NMSTracker_User;

/**
 * @method TagsCollection getCollection()
 */
class TagRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(TagsCollection::COL_LABEL);
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->linkRight(
            $this->getLabel(),
            $this->getAdminViewURL(),
            NMSTracker_User::RIGHT_VIEW_TAGS
        );
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    public function getAdminViewURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_PAGE] = TagsScreen::URL_NAME;
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = TagScreen::URL_NAME;
        $params[TagsCollection::PRIMARY_NAME] = $this->getID();

        return Application_Request::getInstance()->buildURL($params);
    }

    public function getAdminSettingsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = TagSettingsScreen::URL_NAME;

        return $this->getAdminViewURL($params);
    }

    public function getAdminPlanetsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = TagPlanetsScreen::URL_NAME;

        return $this->getAdminViewURL($params);
    }

    public function isEditable() : bool
    {
        return true;
    }
}
