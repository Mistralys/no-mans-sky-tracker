<?php

declare(strict_types=1);

namespace NMSTracker\Planets;

use Application_Admin_ScreenInterface;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetAddPOIScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetMapScreen;
use NMSTracker\Outposts\OutpostRecord;
use DBHelper;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetAddOutpostScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetOutpostsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetPOIsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetSettingsScreen;
use NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen\PlanetStatusScreen;
use NMSTracker\ClassFactory;
use NMSTracker\Outposts\OutpostFilterCriteria;
use NMSTracker\PlanetPOIs\PlanetPOIFilterCriteria;
use NMSTracker\PlanetPOIs\PlanetPOIRecord;
use NMSTracker\PlanetPOIs\POICoordinates;
use NMSTracker\PlanetsCollection;
use NMSTracker\PlanetTypes\PlanetTypeRecord;
use NMSTracker\Resources\ResourceFilterCriteria;
use NMSTracker\Resources\ResourceRecord;
use NMSTracker\ResourcesCollection;
use NMSTracker\SentinelLevels\SentinelLevelRecord;
use NMSTracker\SolarSystems\SolarSystemRecord;
use NMSTracker\SolarSystemsCollection;
use NMSTracker\Tags\TagFilterCriteria;
use NMSTracker\Tags\TagRecord;
use NMSTracker\TagsCollection;
use NMSTracker_User;
use UI;
use UI_Label;
use UI_PropertiesGrid;

class PlanetRecord extends DBHelper_BaseRecord
{
    public function getSolarSystem() : SolarSystemRecord
    {
        return ClassFactory::createSolarSystems()->getByID($this->getSolarSystemID());
    }

    public function getSolarSystemID() : int
    {
        return $this->getRecordIntKey(PlanetsCollection::COL_SOLAR_SYSTEM_ID);
    }

    public function getLabel() : string
    {
        return $this->getRecordStringKey(PlanetsCollection::COL_LABEL);
    }

    public function getLabelLinked() : string
    {
        return (string)sb()->linkRight(
            $this->getLabel(),
            $this->getAdminStatusURL(),
            NMSTracker_User::RIGHT_VIEW_PLANETS
        );
    }

    public function getTypeID() : int
    {
        return $this->getRecordIntKey(PlanetsCollection::COL_PLANET_TYPE_ID);
    }

    public function getType() : PlanetTypeRecord
    {
        return ClassFactory::createPlanetTypes()->getByID($this->getTypeID());
    }

    public function getSentinelLevelID() : int
    {
        return $this->getRecordIntKey(PlanetsCollection::COL_SENTINEL_LEVEL_ID);
    }

    public function getSentinelLevel() : SentinelLevelRecord
    {
        return ClassFactory::createSentinelLevels()->getByID($this->getSentinelLevelID());
    }

    public function isScanComplete() : bool
    {
        return $this->getRecordBooleanKey(PlanetsCollection::COL_SCAN_COMPLETE);
    }

    public function isPlanetFallMade() : bool
    {
        return $this->getRecordBooleanKey(PlanetsCollection::COL_PLANET_FALL_MADE);
    }

    public function isMoon() : bool
    {
        return $this->getRecordBooleanKey(PlanetsCollection::COL_IS_MOON);
    }

    public function isOwnDiscovery() : bool
    {
        return $this->getRecordBooleanKey(PlanetsCollection::COL_IS_OWN_DISCOVERY);
    }

    public function getComments() : string
    {
        return $this->getRecordStringKey(PlanetsCollection::COL_COMMENTS);
    }

    public function getFaunaAmount() : ?int
    {
        $value = $this->getRecordStringKey(PlanetsCollection::COL_FAUNA_AMOUNT);
        if($value === 'not_recorded') {
            return null;
        }

        return (int)$value;
    }

    public function getFaunaAmountPretty(bool $short=false) : string
    {
        $amount = $this->getFaunaAmount();

        if($amount === null) {
            if($short) {
                return (string)NMSTracker::icon()
                    ->minus()
                    ->makeMuted()
                    ->setTooltip(t('Not recorded'))
                    ->cursorHelp();
            }
            return (string)sb()->muted(t('Not recorded'));
        }

        return (string)$amount;
    }

    protected function recordRegisteredKeyModified($name, $label, $isStructural, $oldValue, $newValue)
    {
    }



    /**
     * @return OutpostRecord[]
     */
    public function getOutposts() : array
    {
        return $this->getOutpostFilters()->getItemsObjects();
    }

    public function getOutpostFilters() : OutpostFilterCriteria
    {
        return ClassFactory::createOutposts()
            ->getFilterCriteria()
            ->selectPlanet($this);
    }

    public function getAdminStatusURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = PlanetStatusScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    public function getAdminOutpostsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = PlanetOutpostsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    public function getAdminCreateOutpostURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = PlanetAddOutpostScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    public function getAdminPOIsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = PlanetPOIsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    public function getAdminMapURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = PlanetMapScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    public function getAdminCreatePOIURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = PlanetAddPOIScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    public function getAdminSettingsURL(array $params=array()) : string
    {
        $params[Application_Admin_ScreenInterface::REQUEST_PARAM_ACTION] = PlanetSettingsScreen::URL_NAME;

        return $this->getAdminURL($params);
    }

    public function getAdminURL(array $params=array()) : string
    {
        $params[PlanetsCollection::PRIMARY_NAME] = $this->getID();

        return $this->getSolarSystem()->getAdminPlanetsURL($params);
    }

    public function injectProperties(UI_PropertiesGrid $grid) : void
    {
        $grid->add(t('Planet type'), $this->getType()->getLabelLinked());

        $grid->add(t('Sentinels'), $this->getSentinelLevel()->getLabelLinked());

        $grid->add(t('Fauna count'), $this->getFaunaAmountPretty());

        $grid->addBoolean(t('Scan complete?'), $this->isScanComplete())
            ->makeYesNo()
            ->makeColorsNeutral();

        $grid->add(t('Comments'), $this->getComments())
            ->ifEmpty(sb()->muted(t('No comments specified.')));
    }

    public function countOutposts() : int
    {
        return $this->getOutpostFilters()->countItems();
    }

    public function countOutpostsPretty() : string
    {
        $count = $this->countOutposts();

        if($count > 0)
        {
            return (string)$count;
        }

        return (string)NMSTracker::icon()
            ->minus()
            ->makeMuted()
            ->setTooltip(t('No outposts'))
            ->cursorHelp();
    }

    // region: Resources management

    /**
     * @return ResourceRecord[]
     */
    public function getResources() : array
    {
        return $this->getResourceFilters()->getItemsObjects();
    }

    public function getResourceFilters() : ResourceFilterCriteria
    {
        return ClassFactory::createResources()
            ->getFilterCriteria()
            ->selectPlanet($this);
    }

    public function getResourceIDs() : array
    {
        return DBHelper::createFetchMany(PlanetsCollection::TABLE_RESOURCES)
            ->selectColumn(ResourcesCollection::PRIMARY_NAME)
            ->whereValue(PlanetsCollection::PRIMARY_NAME, $this->getID())
            ->fetchColumnInt(ResourcesCollection::PRIMARY_NAME);
    }

    public function countResources() : int
    {
        return count($this->getResourceIDs());
    }

    /**
     * @param array<int,int|string> $resourceIDs
     * @return void
     */
    public function updateResourcesFromForm(array $resourceIDs) : void
    {
        DBHelper::requireTransaction('Update planet resources');

        $existing = $this->getResourceIDs();
        $collection = ClassFactory::createResources();

        // Add new resources, if they do not exist already
        foreach($resourceIDs as $newResourceID)
        {
            $newResourceID = (int)$newResourceID;

            if(in_array($newResourceID, $existing, true))
            {
                continue;
            }

            $this->addResource($collection->getByID($newResourceID));
        }

        // Remove existing resources that do not exist in the new list
        foreach($existing as $oldResourceID)
        {
            if(!in_array($oldResourceID, $resourceIDs, false))
            {
                $this->removeResource($collection->getByID($oldResourceID));
            }
        }
    }

    public function addResource(ResourceRecord $resource) : self
    {
        if($this->hasResource($resource)) {
            return $this;
        }

        $this->log(
            'Resources | Adding resource [#%s %s].',
            $resource->getID(),
            $resource->getLabel()
        );

        DBHelper::insertDynamic(
            PlanetsCollection::TABLE_RESOURCES,
            array(
                PlanetsCollection::PRIMARY_NAME => $this->getID(),
                SolarSystemsCollection::PRIMARY_NAME => $this->getSolarSystemID(),
                ResourcesCollection::PRIMARY_NAME => $resource->getID()
            )
        );

        return $this;
    }

    public function removeResource(ResourceRecord $resource) : self
    {
        if(!$this->hasResource($resource)) {
            return $this;
        }

        $this->log(
            'Resources | Removing resource [#%s %s].',
            $resource->getID(),
            $resource->getLabel()
        );

        DBHelper::deleteRecords(
            PlanetsCollection::TABLE_RESOURCES,
            array(
                PlanetsCollection::PRIMARY_NAME => $this->getID(),
                ResourcesCollection::PRIMARY_NAME => $resource->getID()
            )
        );

        return $this;
    }

    public function hasResource(ResourceRecord $resource) : bool
    {
        return in_array($resource->getID(), $this->getResourceIDs(), true);
    }

    // endregion

    public function getMoonIcon() : string
    {
        if($this->isMoon()) {
            return (string)UI::label(t('Moon'));
        }

        return '';
    }

    public function getOwnershipBadge() : ?UI_Label
    {
        if($this->isOwnDiscovery()) {
            return UI::label('')
                ->setIcon(NMSTracker::icon()->ownDiscovery())
                ->setTooltip(t('You discovered this.'))
                ->makeSuccess();
        }

        return null;
    }

    /**
     * @return PlanetPOIRecord[]
     */
    public function getPOIs() : array
    {
        return $this->getPOIFilters()->getItemsObjects();
    }

    public function getPOIFilters() : PlanetPOIFilterCriteria
    {
        return ClassFactory::createPlanetPOIs()
            ->getFilterCriteria()
            ->selectPlanet($this);
    }

    /**
     * Retrieves the maximum latitude and longitude values
     * registered by planet POIs and outposts, in any direction.
     *
     * @return array{longitudeMax:float,longitudeMin:float,latitudeMax:float,latitudeMin:float}
     */
    public function getMapScale() : array
    {
        $longitudeMax = -9000;
        $longitudeMin = 9000;
        $latitudeMax = -9000;
        $latitudeMin = 9000;

        $items = array_merge($this->getOutposts(), $this->getPOIs());
        foreach($items as $item)
        {
            $valLongitude = $item->getLongitude();
            $valLatitude = $item->getLatitude();

            if($valLatitude > $latitudeMax) { $latitudeMax = $valLatitude; }
            if($valLongitude > $longitudeMax) { $longitudeMax = $valLongitude; }
            if($valLatitude < $latitudeMin) { $latitudeMin = $valLatitude; }
            if($valLongitude < $longitudeMin) { $longitudeMin = $valLongitude; }
        }

        if($longitudeMax === -9000) { $longitudeMax = 0; }
        if($longitudeMin === 9000) { $longitudeMin = 0; }
        if($latitudeMax === -9000) { $latitudeMax = 0; }
        if($latitudeMin === -9000) { $latitudeMin = 0; }

        return array(
            'longitudeMax' => ceil($longitudeMax),
            'longitudeMin' => floor($longitudeMin),
            'latitudeMax' => ceil($latitudeMax),
            'latitudeMin' => floor($latitudeMin)
        );
    }

    // region: Tags management

    public function countTags() : int
    {
        return count($this->getTagIDs());
    }

    /**
     * @param string[] $tagIDs
     * @return void
     */
    public function updateTagsFromForm(array $tagIDs) : void
    {
        // Remove all existing tags
        DBHelper::deleteRecords(
            PlanetsCollection::TABLE_TAGS,
            array(
                PlanetsCollection::PRIMARY_NAME => $this->getID()
            )
        );

        $collection = ClassFactory::createTags();

        foreach($tagIDs as $tagID)
        {
            $this->addTag($collection->getByID((int)$tagID));
        }
    }

    public function addTag(TagRecord $tag) : void
    {
        if($this->hasTag($tag))
        {
            return;
        }

        DBHelper::insertDynamic(
            PlanetsCollection::TABLE_TAGS,
            array(
                PlanetsCollection::PRIMARY_NAME => $this->getID(),
                TagsCollection::PRIMARY_NAME => $tag->getID()
            )
        );
    }

    public function hasTag(TagRecord $tag) : bool
    {
        return in_array($tag->getID(), $this->getTagIDs());
    }

    /**
     * @return int[]
     */
    public function getTagIDs() : array
    {
        return DBHelper::fetchAllKeyInt(
            TagsCollection::PRIMARY_NAME,
            PlanetFilterCriteria::createStatement("
                SELECT
                    {tag_primary}
                FROM
                    {table_planets_tags}
                WHERE
                    {planet_primary}=:planet_primary"
            ),
            array(
                'planet_primary' => $this->getID()
            )
        );
    }

    // endregion
    public function getTagFilters() : TagFilterCriteria
    {
        return ClassFactory::createTags()
            ->getFilterCriteria()
            ->selectPlanet($this);
    }
}
