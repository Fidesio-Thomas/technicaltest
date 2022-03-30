<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route(path="/index.html", name="index")
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $latestComments = $entityManager->getRepository(Comment::class)->getLatestComments();

        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render(
            'home/index.html.twig',
            [
                'latestComments' => $latestComments,
                'articles' => $articles
            ]
        );
    }
}
