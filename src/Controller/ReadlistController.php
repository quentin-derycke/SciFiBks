<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Form\BookType;
use App\Form\MarkType;
use App\Entity\Readlist;
use App\Form\ReadlistType;
use App\Repository\MarkRepository;
use App\Repository\ReadlistRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    #[IsGranted('ROLE_USER')]
    public function index(ReadlistRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $readlists = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]), /* query */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/readlist/index.html.twig', [
            'readlists' => $readlists
        ]);
    }
    #[Route('/readlist/public', name: 'readlist.public', methods: ['GET'])]
    public function indexPublic(PaginatorInterface $paginator, ReadlistRepository $repository, Request $request): Response
    {

        $readlists = $paginator->paginate($repository->findPublicReadlist(null));

        return $this->render('pages/readlist/index_public.html.twig', [
            'readlists' => $readlists,
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        ]);
    }


    /**
     * This controller allow to see readlist in  public state visibility
     */
    #[Security("is_granted('ROLE_USER') and readlist.isIsPublic() === true")]
    #[Route('/readlist/{id}', name: 'readlist.show', methods: ['GET', 'POST'])]
    public function show(
        Readlist $readlist,
        Request $request,
        MarkRepository $markRepository,
        EntityManagerInterface $manager
    ): Response {

        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mark->setUser($this->getUser())
                ->setReadlist($readlist);

            $existingMark = $markRepository->findOneBy([
                'user' => $this->getUser(),
                'readlist' => $readlist
            ]);

            if (!$existingMark) {
                $manager->persist($mark);
            } else {
                $existingMark->setMark(
                    $form->getData()->getMark()
                );
            }
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre note a bien été pris en compte.'
            );
            return $this->redirectToRoute('readlist.show', ['id' => $readlist->getId()]);
        }

        return $this->render('pages/readlist/show.html.twig', [
            'readlist' => $readlist,
            'form' => $form->createView()
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
    #[IsGranted('ROLE_USER')]
    public function new(EntityManagerInterface $manager, Request $request): Response
    {
        $readlist = new Readlist();
        $form = $this->createForm(ReadlistType::class, $readlist);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $readlist = $form->getData();
            $readlist->setUser($this->getUser());
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
    #[Security("is_granted('ROLE_USER') and user === readlist.getUser()")]
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