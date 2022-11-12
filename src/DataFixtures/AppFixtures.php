<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     *
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $book = new Book();
            $book->setName($this->faker->words(3, true))
                ->setAuthor($this->faker->name())
                ->setYear($this->faker->dateTime())
                ->setCreateAt(date_create_immutable());
            $manager->persist($book);
        }
        $manager->flush();
    }
}
