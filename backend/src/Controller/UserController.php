<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    public function __construct(
        private DocumentManager $dm
    ) {
    }

    #[Route('/users', name: 'users_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->dm->getRepository(User::class)->findAll();

        $data = array_map(function (User $user) {
            return [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'role' => $user->getRole(),
                'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            ];
        }, $users);

        return $this->json($data);
    }

    #[Route('/users/{id}', name: 'users_show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $user = $this->dm->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'role' => $user->getRole(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/users', name: 'users_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['name'])) {
            return $this->json([
                'error' => 'Email and name are required'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setRole($data['role'] ?? 'user');

        $this->dm->persist($user);
        $this->dm->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'role' => $user->getRole(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ], Response::HTTP_CREATED);
    }

    #[Route('/users/{id}', name: 'users_update', methods: ['PUT', 'PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $user = $this->dm->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }
        if (isset($data['role'])) {
            $user->setRole($data['role']);
        }

        $this->dm->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'role' => $user->getRole(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/users/{id}', name: 'users_delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $user = $this->dm->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $this->dm->remove($user);
        $this->dm->flush();

        return $this->json(['message' => 'User deleted successfully']);
    }
}
