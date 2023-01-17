<?php
/**
* File containing the {@see NMSTracker\CustomIcon} class.
*
* @package NMSTracker
* @subpackage User Interface
* @see NMSTracker\CustomIcon
*
* @template-version 1
*/

declare(strict_types=1);

namespace NMSTracker;

use UI_Icon;

/**
* Custom icon class for application-specific icons. Extends
* the framework's icon class, so has the capability to both
* overwrite existing icons and to add new ones.
*
* @package NMSTracker
* @subpackage User Interface
* @author Sebastian Mordziol <s.mordziol@mistralys.eu>
* @see UI_Icon
*/
class CustomIcon extends UI_Icon
{
    // region: Icon type methods
    
    /**
     * @return $this
     */
    public function coordinates() : self { return $this->setType('arrows-alt'); }
    /**
     * @return $this
     */
    public function outpost() : self { return $this->setType('campground', 'fas'); }
    /**
     * @return $this
     */
    public function overview() : self { return $this->setType('list-alt', 'far'); }
    /**
     * @return $this
     */
    public function ownDiscovery() : self { return $this->setType('star', 'fas'); }
    /**
     * @return $this
     */
    public function planet() : self { return $this->setType('globe-europe', 'fas'); }
    /**
     * @return $this
     */
    public function planetType() : self { return $this->setType('globe', 'fas'); }
    /**
     * @return $this
     */
    public function pointsOfInterest() : self { return $this->setType('map-marked-alt', 'fas'); }
    /**
     * @return $this
     */
    public function resources() : self { return $this->setType('shapes', 'fas'); }
    /**
     * @return $this
     */
    public function services() : self { return $this->setType('truck-monster', 'fas'); }
    /**
     * @return $this
     */
    public function solarSystem() : self { return $this->setType('sun', 'fas'); }
    
    // endregion
}
