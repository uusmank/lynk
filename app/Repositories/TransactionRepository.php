<?php
namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction
    {
        $transaction = Transaction::create($data);
        return $transaction;
    }

    public function getAll(): array
    {
        return Transaction::all()->toArray();
    }

    public function userTransactions($userId) : array
    {
        return Transaction::where('payer_id', $userId)->get()->toArray();
    }

    public function updateStatus(int $id): Transaction
    {
        $transaction = $this->getTransactionById($id);
        $transaction->status;
        $transaction->save();
        return $transaction;
    }

    public function generateMonthlyReports($startDate, $endDate): array
    {
         //Log::info(
        return Transaction::select(
            DB::raw('YEAR(due_on) as year'),
            DB::raw('MONTH(due_on) as month'),
            DB::raw('SUM(CASE WHEN status = "Paid" THEN transactions.amount ELSE 0 END) as paid_amount'),
            DB::raw('SUM(CASE WHEN status = "Outstanding" THEN transactions.amount ELSE 0 END) as outstanding_amount'),
            DB::raw('SUM(CASE WHEN status = "Overdue" THEN transactions.amount ELSE 0 END) as overdue_amount')
        )
            ->whereBetween('due_on', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(due_on)'),DB::raw('MONTH(due_on)'))
            ->get()->toArray();
        //return [];
    }

    public function getTransactionById(int $id): Transaction
    {
        return Transaction::find($id);
    }
}
