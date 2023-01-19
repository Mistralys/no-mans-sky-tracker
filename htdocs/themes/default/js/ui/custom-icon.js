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
    
    Coordinates:function() { return this.SetType('arrows-alt'); },
    Map:function() { return this.SetType('map', 'far'); },
    Outpost:function() { return this.SetType('campground', 'fas'); },
    Overview:function() { return this.SetType('list-alt', 'far'); },
    OwnDiscovery:function() { return this.SetType('star', 'fas'); },
    Planet:function() { return this.SetType('globe-europe', 'fas'); },
    PlanetType:function() { return this.SetType('globe', 'fas'); },
    PointsOfInterest:function() { return this.SetType('map-marked-alt', 'fas'); },
    Resources:function() { return this.SetType('shapes', 'fas'); },
    Services:function() { return this.SetType('truck-monster', 'fas'); },
    SolarSystem:function() { return this.SetType('sun', 'fas'); },
    SpaceStation:function() { return this.SetType('satellite', 'fas'); },

    // endregion
};

CustomIcon = UI_Icon.extend(CustomIcon);
