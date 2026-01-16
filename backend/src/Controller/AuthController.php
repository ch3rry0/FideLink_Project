<?php

namespace App\Controller;

use App\Document\Customer;
use App\Document\Merchant;
use App\Document\Admin;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth', name: 'api_auth_')]
class AuthController extends AbstractController
{
    public function __construct(
        private DocumentManager $dm
    ) {
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['identifier']) || !isset($data['password'])) {
            return $this->json(['error' => 'Identifiant et mot de passe requis'], Response::HTTP_BAD_REQUEST);
        }

        $identifier = $data['identifier']; // Peut être email, fdl_id ou merchant_id
        $password = $data['password'];

        // Chercher dans les 3 collections
        $user = null;
        $userType = null;

        // 1. Chercher dans customers (par email ou fdl_id)
        $customer = $this->dm->getRepository(Customer::class)->findOneBy(['email' => $identifier]);
        if (!$customer) {
            $customer = $this->dm->getRepository(Customer::class)->findOneBy(['fdl_id' => $identifier]);
        }
        if ($customer && password_verify($password, $customer->getPassword())) {
            $user = $customer;
            $userType = 'customer';
        }

        // 2. Chercher dans merchants (par email ou merchant_id)
        if (!$user) {
            $merchant = $this->dm->getRepository(Merchant::class)->findOneBy(['email' => $identifier]);
            if (!$merchant) {
                $merchant = $this->dm->getRepository(Merchant::class)->findOneBy(['merchant_id' => $identifier]);
            }
            if ($merchant && password_verify($password, $merchant->getPassword())) {
                $user = $merchant;
                $userType = 'merchant';
            }
        }

        // 3. Chercher dans admins (par email uniquement)
        if (!$user) {
            $admin = $this->dm->getRepository(Admin::class)->findOneBy(['email' => $identifier]);
            if ($admin && password_verify($password, $admin->getPassword())) {
                $user = $admin;
                $userType = 'admin';
            }
        }

        if (!$user) {
            return $this->json(['error' => 'Identifiant ou mot de passe incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        // Retourner les données de l'utilisateur selon le type
        $userData = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'type' => $userType,
        ];

        if ($userType === 'customer') {
            $userData['fdl_id'] = $user->getFdlId();
            $userData['pointsBal'] = $user->getPointsBal();
            $userData['age'] = $user->getAge();
        } elseif ($userType === 'merchant') {
            $userData['merchant_id'] = $user->getMerchantId();
            $userData['pointVal'] = $user->getPointVal();
            $userData['miniThresh'] = $user->getMiniThresh();
            $userData['bio'] = $user->getBio();
            $location = $user->getLoc();
            $userData['loc'] = $location ? [
                'address' => $location->getAddress(),
                'zip' => $location->getZip(),
                'city' => $location->getCity(),
            ] : null;
        }

        return $this->json([
            'success' => true,
            'user' => $userData
        ]);
    }

    #[Route('/check', name: 'check', methods: ['GET'])]
    public function check(): JsonResponse
    {
        // Endpoint pour vérifier si l'utilisateur est connecté (à implémenter avec sessions plus tard)
        return $this->json(['authenticated' => false]);
    }
}
