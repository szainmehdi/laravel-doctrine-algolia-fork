<?php

namespace Zain\LaravelDoctrine\Algolia\TestCase;

use Zain\LaravelDoctrine\Algolia\BaseTest;
use Zain\LaravelDoctrine\Algolia\Exception\EntityNotFoundInObjectID;
use Zain\LaravelDoctrine\Algolia\Exception\InvalidEntityForAggregator;
use Zain\LaravelDoctrine\Algolia\TestApp\Entity\ContentAggregator;
use Zain\LaravelDoctrine\Algolia\TestApp\Entity\EmptyAggregator;
use Zain\LaravelDoctrine\Algolia\TestApp\Entity\Post;

class AggregatorTest extends BaseTest
{
    public function testGetEntities()
    {
        $entites = EmptyAggregator::getEntities();

        $this->assertEquals([], $entites);
    }

    public function testGetEntityClassFromObjectID()
    {
        $this->expectException(EntityNotFoundInObjectID::class);
        EmptyAggregator::getEntityClassFromObjectID('test');
    }

    public function testContructor()
    {
        $this->expectException(InvalidEntityForAggregator::class);
        $post                = new Post();
        $compositeAggregator = new ContentAggregator($post, ['objectId', 'url']);
    }
}
