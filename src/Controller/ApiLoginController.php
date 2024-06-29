<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(
        UserRepository $userRepository,
        JWTTokenManagerInterface $JWTTokenManager,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        $user = $userRepository->findOneBy(['email' => $username]);

        if (!$user || !password_verify($password, $user->getPassword())) {
            return $this->json(['message' => 'Invalid login credentials.']);
        }

        $token = $JWTTokenManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
