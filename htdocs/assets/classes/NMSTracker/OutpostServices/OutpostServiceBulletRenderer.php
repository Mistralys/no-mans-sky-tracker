<?php

declare(strict_types=1);

namespace NMSTracker\OutpostServices;

use UI;
use UI_Renderable;
use UI_Traits_RenderableGeneric;

class OutpostServiceBulletRenderer extends UI_Renderable
{
    use UI_Traits_RenderableGeneric;

    private OutpostServiceContainer $container;

    public function __construct(OutpostServiceContainer $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    protected function _render()
    {
        $services = $this->container->getAll();

        if(empty($services)) {
            return (string)$this->getUI()->createMessage('')
                ->setMessage(t('No services have been registered.'))
                ->makeSlimLayout()
                ->makeNotDismissable()
                ->makeInfo()
                ->enableIcon();
        }

        $items = array();
        foreach($services as $service)
        {
            $items[] = sb()->add($service->getLabelLinked());
        }

        return '<ul class="unstyled"><li>'.implode('</li><li>', $items).'</li></ul>';
    }
}