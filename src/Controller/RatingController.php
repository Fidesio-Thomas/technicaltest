<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\CommentRating;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route(path="/rate/comment", name="rate_comment")
     *
     * @return Response
     */
    public function rateComment(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $commentId = $request->request->get('commentId');
        $comment = $entityManager->getRepository(Comment::class)->find($commentId);

        $rating = $request->request->get('rating');

        $commentRating = new CommentRating();
        $commentRating
            ->setComment($comment)
            ->setRating($rating)
        ;

        $entityManager->persist($commentRating);
        $entityManager->flush();

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }
}