<?php

declare(strict_types=1);

namespace NMSTracker\Tags;

use NMSTracker\ClassFactory;

class TagContainer
{
    /**
     * @var TagRecord[]
     */
    private array $tags = array();

    private function __construct()
    {
    }

    public function createBulletListRenderer() : TagBulletRenderer
    {
        return new TagBulletRenderer($this);
    }

    /**
     * @param TagRecord[]|TagRecord|TagFilterCriteria|NULL $value
     * @return TagContainer
     */
    public static function create($value=null) : TagContainer
    {
        $container = new self();

        if($value instanceof TagFilterCriteria)
        {
            $container->addFilters($value);
        }
        else if($value instanceof TagRecord)
        {
            $container->addTag($value);
        }
        else if (is_array($value))
        {
            $container->addTags($value);
        }

        return $container;
    }

    /**
     * @return TagRecord[]
     */
    public function getAll() : array
    {
        return array_values($this->tags);
    }

    public function addTag(TagRecord $tag) : self
    {
        $this->tags[$tag->getID()] = $tag;
        return $this;
    }

    public function addFilters(TagFilterCriteria $filterCriteria) : self
    {
        return $this->addTags($filterCriteria->getItemsObjects());
    }

    /**
     * @param TagRecord[] $tags
     * @return $this
     */
    public function addTags(array $tags) : self
    {
        foreach($tags as $tag)
        {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * @param array<int,int|string> $ids
     * @return $this
     */
    public function addIDs(array $ids) : self
    {
        foreach($ids as $id)
        {
            $this->addID((int)$id);
        }

        return $this;
    }

    public function addID(int $id) : self
    {
        return $this->addTag(ClassFactory::createTags()->getByID($id));
    }
}
