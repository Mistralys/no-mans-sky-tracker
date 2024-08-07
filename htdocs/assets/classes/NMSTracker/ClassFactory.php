<?php

declare(strict_types=1);

namespace NMSTracker;

use AppUtils\ClassHelper;
use DBHelper;
use NMSTracker;
use NMSTracker\Resources\ResourceTypesCollection;
use NMSTracker\SentinelLevels\SentinelAggressionLevels;
use NMSTracker_User;

class ClassFactory
{
    public static function createDriver() : NMSTracker
    {
        return ClassHelper::requireObjectInstanceOf(
            NMSTracker::class,
            NMSTracker::getInstance()
        );
    }

    public static function createUser() : NMSTracker_User
    {
        return self::createDriver()->getUser();
    }

    public static function createSolarSystems() : SolarSystemsCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            SolarSystemsCollection::class,
            DBHelper::createCollection(SolarSystemsCollection::class)
        );
    }

    public static function createPlanets() : PlanetsCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            PlanetsCollection::class,
            DBHelper::createCollection(PlanetsCollection::class)
        );
    }

    public static function createRaces() : RacesCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            RacesCollection::class,
            DBHelper::createCollection(RacesCollection::class)
        );
    }

    public static function createPlanetTypes() : PlanetTypesCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            PlanetTypesCollection::class,
            DBHelper::createCollection(PlanetTypesCollection::class)
        );
    }

    public static function createSentinelLevels() : SentinelLevelsCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            SentinelLevelsCollection::class,
            DBHelper::createCollection(SentinelLevelsCollection::class)
        );
    }

    public static function createResources() : ResourcesCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            ResourcesCollection::class,
            DBHelper::createCollection(ResourcesCollection::class)
        );
    }

    public static function createOutposts() : OutpostsCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            OutpostsCollection::class,
            DBHelper::createCollection(OutpostsCollection::class)
        );
    }

    public static function createOutpostRoles() : OutpostRolesCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            OutpostRolesCollection::class,
            DBHelper::createCollection(OutpostRolesCollection::class)
        );
    }

    public static function createOutpostServices() : OutpostServicesCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            OutpostServicesCollection::class,
            DBHelper::createCollection(OutpostServicesCollection::class)
        );
    }

    public static function createPlanetPOIs() : PlanetPOIsCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            PlanetPOIsCollection::class,
            DBHelper::createCollection(PlanetPOIsCollection::class)
        );
    }

    public static function createStarTypes() : StarTypesCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            StarTypesCollection::class,
            DBHelper::createCollection(StarTypesCollection::class)
        );
    }

    public static function createResourceTypes() : ResourceTypesCollection
    {
        return ResourceTypesCollection::getInstance();
    }

    public static function createSpaceStations() : SpaceStationsCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            SpaceStationsCollection::class,
            DBHelper::createCollection(SpaceStationsCollection::class)
        );
    }

    public static function createSentinelAggressionLevels() : SentinelAggressionLevels
    {
        return SentinelAggressionLevels::getInstance();
    }

    public static function createTags() : TagsCollection
    {
        return ClassHelper::requireObjectInstanceOf(
            TagsCollection::class,
            DBHelper::createCollection(TagsCollection::class)
        );
    }
}
