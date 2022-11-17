<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\Readlist;
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
        // Books
        $books = [];
        for ($i = 0; $i < 50; $i++) {
            $book = new Book();
            $book->setName($this->faker->words(3, true))
                ->setAuthor($this->faker->name())
                ->setYear($this->faker->dateTime())
            ->setResume($this->faker->paragraph());
            $books[] = $book;
            $manager->persist($book);
        }

        // ReadList
        for ($i = 0; $i < 25; $i++) {
    $readlist = new Readlist();
    $readlist->setName($this->faker->word())
    ->setDescription($this->faker->paragraph())
    ->setIsFavorite($this->faker->boolean());
    
    for ($k=0; $k < mt_rand(5,15); $k ++){
        $readlist->addBook($books[mt_rand(0, count($books) - 1 )]);
    }
    $manager->persist($readlist);

        }
        $manager->flush();
    }
}
