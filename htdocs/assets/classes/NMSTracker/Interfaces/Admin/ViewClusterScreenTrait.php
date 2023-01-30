<?php

declare(strict_types=1);

namespace NMSTracker\Interfaces;

use NMSTracker\ClassFactory;
use NMSTracker\Clusters\ClusterRecord;

trait ViewClusterScreenTrait
{
    public function getCluster() : ClusterRecord
    {
        $collection = ClassFactory::createClusters();
        $cluster = $collection->getByRequest();

        if($cluster !== null) {
            return $cluster;
        }

        $this->redirectWithErrorMessage(
            t('No such solar system cluster found.'),
            $collection->getAdminListURL()
        );
    }


}
