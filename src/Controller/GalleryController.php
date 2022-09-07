<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery', name: 'app_gallery')]
    public function index( ArticlesRepository $articlesRepository): Response
    {

        return $this->render('gallery/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
            'controller_name' => 'GalleryController',
        ]);
    }
}
