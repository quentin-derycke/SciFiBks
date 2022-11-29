<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * This controller allow us to edit user's profile
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === choosenUser ")]
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(
        User $choosenUser,
     Request $request,
      EntityManagerInterface $manager,
       UserPasswordHasherInterface $hasher): Response
    {

        // if (!$this->getUser()) {
        //     return $this->redirectToRoute('security.login');
        // }

        // if ($this->getUser() !== $user) {
        //     return $this->redirectToRoute("readlist.index");
        // }


        $form = $this->createForm(UserType::class, $choosenUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() &&  $form->isValid()) {
            if ($hasher->isPasswordValid($choosenUser, $form->getData()->getPlainPAssword())) {

                $choosenUser = $form->getData();
                $manager->persist($choosenUser);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Modification réussi'
                );
                return $this->redirectToRoute('readlist.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe incorrect'
                );
            }
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }






    /**
     * This controller allow us to edit User password
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition-mot-de-passe/{id}', 'user.edit.password', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === choosenUser ")]

    public function editPassword(
        User $choosenUser,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {
        // if (!$this->getUser()) {
        //     return $this->redirectToRoute('security.login');
        // }

        // if ($this->getUser() !== $user) {
        //     return $this->redirectToRoute("readlist.index");
        // }
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($hasher->isPasswordValid($choosenUser, $form->getData()['plainPassword'])) {
                $choosenUser->setUpdatedAt(new \DateTimeImmutable());
                $choosenUser->setPlainPassword(
                    $form->getdata()['newPassword']
                );

                $manager->persist($choosenUser);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié'
                );
                return $this->redirectToRoute('readlist.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe est  incorrect'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
