<?php

declare(strict_types=1);

namespace NMSTracker\Clusters;

use Application_Admin_ScreenInterface;
use DBHelper_BaseRecord;
use NMSTracker\Area\ClustersScreen\ClusterScreen;
use NMSTracker\Area\ClustersScreen\ClusterScreen\ClusterStatusScreen;
use NMSTracker\ClustersCollection;
use NMSTracker_User;
use UI_Icon;

/**
 * @property ClustersCollection $collection
 */
class ClusterRecord extends DBHelper_BaseRecord
{
    public function getLabel() : string
    {
        return $this->getRecordStringKey(ClustersCollection::COL_LABEL);
    }

    public function getIcon() : UI_Icon
    {
        return $this->collection->getIcon();
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->linkRight(
            $this->getLabel(),
            $this->getAdminStatusURL(),
            NMSTracker_User::RIGHT_VIEW_CLUSTERS
        );
    }

    public function getComments() : string
    {
        return $this->getRecordStringKey(ClustersCollection::COL_COMMENTS);
    }

    public function getCoreDistance() : int
    {
        return $this->getRecordIntKey(ClustersCollection::COL_CORE_DISTANCE);
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }

    /**
     * @param array<string,string|number|NULL> $params
     * @return string
     */
    public function getAdminURL(array $params=array()) : string
    {
        $params[ClustersCollection::PRIMARY_NAME] = $this->getID();
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_MODE] = ClusterScreen::URL_NAME;

        return $this->collection->getAdminURL($params);
    }

    /**
     * @param array<string,string|number|NULL> $params
     * @return string
     */
    public function getAdminStatusURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_SUBMODE] = ClusterStatusScreen::URL_NAME;

        return $this->getAdminURL($params);
    }
}
