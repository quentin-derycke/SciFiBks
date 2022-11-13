<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    /**
     * This function dispaly all books
     *
     * @param BookRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/book', name: 'book.index', methods: ['GET'])]
    public function index(BookRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $books = $paginator->paginate(
            $repository->findAll(), /* query */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/book/index.html.twig', [
            'books' => $books
        ]);
    }

    #[Route('/book/add', 'book.add', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $manager, Request $request): Response
    {


        $book = new Book();


        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();

            $manager->persist($book);
            $manager->flush();

            $this->addFlash(
                'success',
                'Document has successfully added  !'
            );
        }

        return $this->render('pages/book/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
