<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\BlogPost;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class LoadBlogPosts.
 */
class LoadBlogPosts extends AbstractFixture
{
    const POSTS_COUNT = 20;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < static::POSTS_COUNT; ++$i) {
            $post = new BlogPost();
            $post->setTitle($faker->sentence);
            $post->setContent($faker->paragraphs(5, true));

            $manager->persist($post);
        }

        $manager->flush();
    }
}
