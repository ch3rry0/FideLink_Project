<?php

namespace App\Controller;

use App\Document\Transaction;
use App\Document\Customer;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/transactions', name: 'api_transactions_')]
class TransactionController extends AbstractController
{
    public function __construct(
        private DocumentManager $dm
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $customerId = $request->query->get('customer_id');
        $merchantId = $request->query->get('merchant_id');

        $criteria = [];
        if ($customerId) {
            $criteria['customer_id'] = $customerId;
        }
        if ($merchantId) {
            $criteria['merchant_id'] = $merchantId;
        }

        $transactions = $this->dm->getRepository(Transaction::class)->findBy(
            $criteria,
            ['transacDate' => 'DESC']
        );

        $data = array_map(function (Transaction $transaction) {
            return $this->serializeTransaction($transaction);
        }, $transactions);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        $transaction = $this->dm->getRepository(Transaction::class)->find($id);

        if (!$transaction) {
            return $this->json(['error' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializeTransaction($transaction));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['customer_id']) || !isset($data['merchant_id'])) {
            return $this->json(['error' => 'customer_id and merchant_id are required'], Response::HTTP_BAD_REQUEST);
        }

        $transaction = new Transaction();
        $transaction->setCustomerId($data['customer_id']);
        $transaction->setMerchantId($data['merchant_id']);
        $transaction->setType($data['type'] ?? null);
        $transaction->setAmount($data['amount'] ?? null);
        $transaction->setPts($data['pts'] ?? 0);
        $transaction->setNote($data['note'] ?? null);

        // Mettre à jour le solde du client si nécessaire
        if (isset($data['type']) && isset($data['pts'])) {
            $customer = $this->dm->getRepository(Customer::class)->find($data['customer_id']);
            if ($customer) {
                if ($data['type'] == 1) {
                    // Gain de points
                    $customer->setPointsBal($customer->getPointsBal() + $data['pts']);
                } elseif ($data['type'] == 0) {
                    // Dépense de points
                    $customer->setPointsBal($customer->getPointsBal() - $data['pts']);
                }
            }
        }

        $this->dm->persist($transaction);
        $this->dm->flush();

        return $this->json($this->serializeTransaction($transaction), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $transaction = $this->dm->getRepository(Transaction::class)->find($id);

        if (!$transaction) {
            return $this->json(['error' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['type'])) {
            $transaction->setType($data['type']);
        }
        if (isset($data['amount'])) {
            $transaction->setAmount($data['amount']);
        }
        if (isset($data['pts'])) {
            $transaction->setPts($data['pts']);
        }
        if (isset($data['note'])) {
            $transaction->setNote($data['note']);
        }

        $this->dm->flush();

        return $this->json($this->serializeTransaction($transaction));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $transaction = $this->dm->getRepository(Transaction::class)->find($id);

        if (!$transaction) {
            return $this->json(['error' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
        }

        $this->dm->remove($transaction);
        $this->dm->flush();

        return $this->json(['message' => 'Transaction deleted successfully']);
    }

    private function serializeTransaction(Transaction $transaction): array
    {
        return [
            'id' => $transaction->getId(),
            'customer_id' => $transaction->getCustomerId(),
            'merchant_id' => $transaction->getMerchantId(),
            'type' => $transaction->getType(),
            'amount' => $transaction->getAmount(),
            'pts' => $transaction->getPts(),
            'tx_pts' => $transaction->getTxPts(),
            'note' => $transaction->getNote(),
            'transacDate' => $transaction->getTransacDate()?->format('Y-m-d H:i:s'),
        ];
    }
}
