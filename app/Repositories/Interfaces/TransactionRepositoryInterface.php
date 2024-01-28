<?php
namespace App\Repositories\Interfaces;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;

    public function getAll(): array;

    public function getTransactionById(int $id): Transaction;

    public function userTransactions(int $userId): array;

    public function updateStatus(int $id): Transaction;

    public function generateMonthlyReports($startDate, $endDate):array;
}
