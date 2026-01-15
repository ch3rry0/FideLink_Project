<?php

namespace App\Controller;

use App\Document\Customer;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customers', name: 'api_customers_')]
class CustomerController extends AbstractController
{
    public function __construct(
        private DocumentManager $dm
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $customers = $this->dm->getRepository(Customer::class)->findAll();

        $data = array_map(function (Customer $customer) {
            return $this->serializeCustomer($customer);
        }, $customers);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $customer = $this->dm->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializeCustomer($customer));
    }

    #[Route('/fdl/{fdl_id}', name: 'show_by_fdl', methods: ['GET'])]
    public function showByFdlId(string $fdl_id): JsonResponse
    {
        $customer = $this->dm->getRepository(Customer::class)->findOneBy(['fdl_id' => $fdl_id]);

        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializeCustomer($customer));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['fdl_id']) || !isset($data['email'])) {
            return $this->json(['error' => 'Name, fdl_id and email are required'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier si le fdl_id existe déjà
        $existing = $this->dm->getRepository(Customer::class)->findOneBy(['fdl_id' => $data['fdl_id']]);
        if ($existing) {
            return $this->json(['error' => 'FideLink ID already exists'], Response::HTTP_CONFLICT);
        }

        $customer = new Customer();
        $customer->setName($data['name']);
        $customer->setFdlId($data['fdl_id']);
        $customer->setEmail($data['email']);
        $customer->setPfp($data['pfp'] ?? null);
        $customer->setPointsBal($data['pointsBal'] ?? 0);

        $this->dm->persist($customer);
        $this->dm->flush();

        return $this->json($this->serializeCustomer($customer), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $customer = $this->dm->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $customer->setName($data['name']);
        }
        if (isset($data['email'])) {
            $customer->setEmail($data['email']);
        }
        if (isset($data['pfp'])) {
            $customer->setPfp($data['pfp']);
        }
        if (isset($data['pointsBal'])) {
            $customer->setPointsBal($data['pointsBal']);
        }

        $this->dm->flush();

        return $this->json($this->serializeCustomer($customer));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $customer = $this->dm->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $this->dm->remove($customer);
        $this->dm->flush();

        return $this->json(['message' => 'Customer deleted successfully']);
    }

    private function serializeCustomer(Customer $customer): array
    {
        return [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'fdl_id' => $customer->getFdlId(),
            'email' => $customer->getEmail(),
            'pfp' => $customer->getPfp(),
            'pointsBal' => $customer->getPointsBal(),
            'createdAt' => $customer->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}