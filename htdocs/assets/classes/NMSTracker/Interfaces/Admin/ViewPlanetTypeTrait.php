<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces\Admin;

use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\PlanetTypes\PlanetTypeRecord;

trait ViewPlanetTypeTrait
{
    public function getPlanetType() : PlanetTypeRecord
    {
        $collection = ClassFactory::createPlanetTypes();
        $type = $collection->getByRequest();

        if($type !== null) {
            return $type;
        }

        $this->redirectWithErrorMessage(
            t('No such planet type found.'),
            $collection->getAdminListURL()
        );
    }

    protected function _handleBreadcrumb() : void
    {
        $type = $this->getPlanetType();

        $this->breadcrumb->appendItem($type->getLabel())
            ->makeLinked($type->getAdminStatusURL());
    }

    protected function _handleHelp() : void
    {
         $this->renderer
             ->getTitle()
             ->setText($this->getPlanetType()->getLabel())
             ->setIcon(NMSTracker::icon()->planetType());
    }
}
