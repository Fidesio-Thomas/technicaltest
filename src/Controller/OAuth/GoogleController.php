<?php

namespace App\Controller\OAuth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * @Route("/connect/google", name="connect_google_start")
     */
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google_main')
            ->redirect();
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheckAction(
        Request $request,
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager
    ) {
        /** @var GoogleClient $client */
        $client = $clientRegistry->getClient('google_main');

        try {
            /** @var GoogleUser $user */
            $user = $client->fetchUser();

            $databaseUser = $entityManager->getRepository(User::class)->findOneBy(
                [
                    'email' => $user->getEmail()
                ]
            );

            if (!$databaseUser) {
                $newUser = new User();

                $newUser
                    ->setEmail($user->getEmail())
                    ->setRoles(['ROLE_USER'])
                ;

                $entityManager->persist($newUser);
                $entityManager->flush();
            }

            return $this->redirectToRoute('index');
        } catch (IdentityProviderException $e) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'An error occured during Google authentication'
                ]
            );
        }
    }
}