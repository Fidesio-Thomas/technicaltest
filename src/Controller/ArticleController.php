<?php

namespace App\Controller;

use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route(name="article_show", path="/article{id}.html", requirements={"id"="\d+"})
     *
     * @param ArticleRepository $articleRepository
     * @param int $id
     * @return Response
     */
    public function show(
        ArticleRepository $articleRepository,
        int $id
    ): Response {
        $article = $articleRepository->find($id);
        $commentForm = $this->createForm(CommentFormType::class);

        return $this->render(
            'article/show.html.twig',
            [
                'article' => $article,
                'commentForm' => $commentForm->createView()
            ]
        );
    }
}