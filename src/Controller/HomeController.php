<?php

namespace App\Controller;

use App\Repository\ReadlistRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/', 'home.index', methods: 'GET')]
    public function index(ReadlistRepository $readlistRepository): Response
    {
        return $this->render('pages/home.html.twig', [
            'readlists' => $readlistRepository->findPublicReadlist(3)
        ]);
    }
}
