<?php

namespace App\Controller;

use App\Entity\Readlist;
use App\Form\BookType;
use App\Form\ReadlistType;
use App\Repository\ReadlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReadlistController extends AbstractController
{

    /**
     *  This controller dispaly all readlists
     *
     * @param ReadlistRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/readlist', name: 'readlist.index', methods: ['GET'])]
    public function index(ReadlistRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $readlists= $paginator->paginate(
            $repository->findAll(), /* query */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/readlist/index.html.twig', [
            'readlists' => $readlists
        ]);
    }


    /**
     * This controller allow us to create a new readlist 
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/readlist/add', name: 'readlist.add', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $manager, Request $request): Response
    {
        $readlist = new Readlist();
        $form = $this->createForm(ReadlistType::class, $readlist);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
           $readlist = $form->getData();

           $manager->persist($readlist);
           $manager->flush();

           $this->addFlash(
            'success',
            'List  successfully created  !'
        );


           return $this->redirectToRoute('readlist.index');
        }
      return $this->render('pages/readlist/new.html.twig', [
        'form' =>  $form->createView()
      ]);
    }

    /**
     * This controller  display a form to edit a document
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */

    #[Route('/readlist/edition/{id}', 'readlist.edit', methods: ['GET', 'POST'])]
    public function edit(Readlist $readlist, Request $request, EntityManagerInterface $manager): Response
    {


        $form = $this->createForm(ReadlistType::class, $readlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $readlist = $form->getData();

            $manager->persist($readlist);
            $manager->flush();

            $this->addFlash(
                'success',
                'List has successfully update !'
            );

            return $this->redirectToRoute('readlist.index');
        }

        return $this->render('pages/readlist/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * This controller  display a form to delete a document
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('readlist/delete/{id}', 'readlist.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Readlist $readlist): Response
    {


        $manager->remove($readlist);
        $manager->flush();

        $this->addFlash(
            'success',
            'Document deleted successfully  !'
        );
        return $this->redirectToRoute('readlist.index');
    }
}


