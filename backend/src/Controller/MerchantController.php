<?php

namespace App\Controller;

use App\Document\Merchant;
use App\Document\Location;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/merchants', name: 'api_merchants_')]
class MerchantController extends AbstractController
{
    public function __construct(
        private DocumentManager $dm
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $merchants = $this->dm->getRepository(Merchant::class)->findAll();

        $data = array_map(function (Merchant $merchant) {
            return $this->serializeMerchant($merchant);
        }, $merchants);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $merchant = $this->dm->getRepository(Merchant::class)->find($id);

        if (!$merchant) {
            return $this->json(['error' => 'Merchant not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializeMerchant($merchant));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'])) {
            return $this->json(['error' => 'Name is required'], Response::HTTP_BAD_REQUEST);
        }

        $merchant = new Merchant();
        $merchant->setName($data['name']);
        $merchant->setPfp($data['pfp'] ?? null);
        $merchant->setBio($data['bio'] ?? null);
        $merchant->setPointVal($data['pointVal'] ?? 1.0);
        $merchant->setMiniThresh($data['miniThresh'] ?? null);

        // Gestion de l'adresse
        if (isset($data['loc'])) {
            $location = new Location();
            $location->setAddress($data['loc']['address'] ?? '');
            $location->setZip($data['loc']['zip'] ?? '');
            $location->setCity($data['loc']['city'] ?? '');
            $merchant->setLoc($location);
        }

        $this->dm->persist($merchant);
        $this->dm->flush();

        return $this->json($this->serializeMerchant($merchant), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $merchant = $this->dm->getRepository(Merchant::class)->find($id);

        if (!$merchant) {
            return $this->json(['error' => 'Merchant not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $merchant->setName($data['name']);
        }
        if (isset($data['pfp'])) {
            $merchant->setPfp($data['pfp']);
        }
        if (isset($data['bio'])) {
            $merchant->setBio($data['bio']);
        }
        if (isset($data['pointVal'])) {
            $merchant->setPointVal($data['pointVal']);
        }
        if (isset($data['miniThresh'])) {
            $merchant->setMiniThresh($data['miniThresh']);
        }
        if (isset($data['loc'])) {
            $location = $merchant->getLoc() ?? new Location();
            if (isset($data['loc']['address'])) $location->setAddress($data['loc']['address']);
            if (isset($data['loc']['zip'])) $location->setZip($data['loc']['zip']);
            if (isset($data['loc']['city'])) $location->setCity($data['loc']['city']);
            $merchant->setLoc($location);
        }

        $this->dm->flush();

        return $this->json($this->serializeMerchant($merchant));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $merchant = $this->dm->getRepository(Merchant::class)->find($id);

        if (!$merchant) {
            return $this->json(['error' => 'Merchant not found'], Response::HTTP_NOT_FOUND);
        }

        $this->dm->remove($merchant);
        $this->dm->flush();

        return $this->json(['message' => 'Merchant deleted successfully']);
    }

    private function serializeMerchant(Merchant $merchant): array
    {
        $location = $merchant->getLoc();
        
        return [
            'id' => $merchant->getId(),
            'name' => $merchant->getName(),
            'pfp' => $merchant->getPfp(),
            'loc' => $location ? [
                'address' => $location->getAddress(),
                'zip' => $location->getZip(),
                'city' => $location->getCity(),
            ] : null,
            'bio' => $merchant->getBio(),
            'pointVal' => $merchant->getPointVal(),
            'miniThresh' => $merchant->getMiniThresh(),
            'createdAt' => $merchant->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
