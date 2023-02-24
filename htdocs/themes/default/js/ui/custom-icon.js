/**
* UI Icon handling class: offers an easy-to-use API
* to create icons for common application tasks.
*
* @package NMSTracker
* @subpackage User Interface
* @author Sebastian Mordziol <s.mordziol@mistralys.eu>
* @class
*
* @template-version 1
*/
var CustomIcon =
{
    // region: Icon methods
    
    Cluster:function() { return this.SetType('cookie', 'fas'); },
    Coordinates:function() { return this.SetType('arrows-alt'); },
    Discoveries:function() { return this.SetType('dragon', 'fas'); },
    Map:function() { return this.SetType('map', 'far'); },
    Outpost:function() { return this.SetType('campground', 'fas'); },
    Overview:function() { return this.SetType('list-alt', 'far'); },
    OwnDiscovery:function() { return this.SetType('star', 'fas'); },
    Planet:function() { return this.SetType('globe-europe', 'fas'); },
    PlanetType:function() { return this.SetType('globe', 'fas'); },
    PointsOfInterest:function() { return this.SetType('map-marked-alt', 'fas'); },
    Resources:function() { return this.SetType('shapes', 'fas'); },
    Sentinels:function() { return this.SetType('virus', 'fas'); },
    Services:function() { return this.SetType('truck-monster', 'fas'); },
    SolarSystem:function() { return this.SetType('sun', 'fas'); },
    SpaceStation:function() { return this.SetType('satellite', 'fas'); },
    Wormhole:function() { return this.SetType('link', 'fas'); },

    // endregion
};

CustomIcon = UI_Icon.extend(CustomIcon);
