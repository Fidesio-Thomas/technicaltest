<?php

namespace App\Controller\API;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route(path="/api/comment/create", name="api_comment_create")
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function postComment(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $commentParameters = json_decode($request->getContent(), true);

        $article = $entityManager->getRepository(Article::class)->find($commentParameters['articleId']);
        $user = $entityManager->getRepository(User::class)->find($commentParameters['userId']);

        $comment = new Comment();
        $comment
            ->setAuthor($user)
            ->setContent($commentParameters['comment'])
            ->setArticle($article)
        ;

        $entityManager->persist($comment);
        $entityManager->flush();

        return new JsonResponse(
            [
                'success' => true
            ]
        );
    }
}