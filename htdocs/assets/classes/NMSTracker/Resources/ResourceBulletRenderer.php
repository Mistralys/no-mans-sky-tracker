<?php

declare(strict_types=1);

namespace NMSTracker\Resources;

use UI_Renderable;
use UI_Traits_RenderableGeneric;

class ResourceBulletRenderer extends UI_Renderable
{
    use UI_Traits_RenderableGeneric;

    private ResourceContainer $container;

    public function __construct(ResourceContainer $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    protected function _render() : string
    {
        $services = $this->container->getAll();

        if(empty($services)) {
            return $this->renderEmpty();
        }

        $items = array();
        foreach($services as $service)
        {
            $items[] = sb()->add($service->getLabelLinked());
        }

        return '<ul class="unstyled"><li>'.implode('</li><li>', $items).'</li></ul>';
    }

    private function renderEmpty() : string
    {
        return (string)$this->getUI()->createMessage('')
            ->setMessage(t('No resources present.'))
            ->makeSlimLayout()
            ->makeNotDismissable()
            ->makeInfo()
            ->enableIcon();
    }
}