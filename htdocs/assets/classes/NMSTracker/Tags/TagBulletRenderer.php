<?php

declare(strict_types=1);

namespace NMSTracker\Tags;

use UI_Renderable;
use UI_Traits_RenderableGeneric;

class TagBulletRenderer extends UI_Renderable
{
    use UI_Traits_RenderableGeneric;

    private TagContainer $container;

    public function __construct(TagContainer $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    protected function _render() : string
    {
        $tags = $this->container->getAll();

        if(empty($tags)) {
            return $this->renderEmpty();
        }

        $items = array();
        foreach($tags as $tag)
        {
            $items[] = sb()->add($tag->getLabelLinked());
        }

        return '<ul class="unstyled"><li>'.implode('</li><li>', $items).'</li></ul>';
    }

    private function renderEmpty() : string
    {
        return (string)$this->getUI()->createMessage('')
            ->setMessage(t('No tags added.'))
            ->makeSlimLayout()
            ->makeNotDismissable()
            ->makeInfo()
            ->enableIcon();
    }
}
