<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Readlist;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     *
     * @var Generator
     */
    private Generator $faker;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
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

         // Users 
         for($i = 0; $i< 10; $i++) {
$user = new User();
$user->setFullName($this->faker->name())
->setPseudo(mt_rand(0,1) === 1 ? $this->faker->firstName() : null)
->setEmail($this->faker->email())
->setRoles(['ROLE_USER']);

$hashPassword = $this->hasher->hashPassword(
    $user,
    'password'
);
 $user->setPassword($hashPassword);

$manager->persist($user);

         }
        $manager->flush();
    }
}
