<?php
namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        $results = DB::select("select year,month,round(sum(paid),2) as paid,round(sum(overdue),2)as overdue,round(sum(outstanding),2) as outstanding from ((SELECT
        MONTH(paid_on) AS month,
        YEAR(paid_on) AS year,
        SUM(payments.amount) AS paid,
        0 AS overdue,
        0 AS outstanding
        FROM payments
        WHERE
        paid_on BETWEEN :paid_on_start_date AND :paid_on_end_date
        GROUP BY
            YEAR(paid_on),MONTH(paid_on))
 UNION
 (SELECT
      MONTH(due_on) AS month,
      YEAR(due_on) AS year,
      0 AS paid,
      SUM(CASE WHEN due_on < CURDATE() THEN transactions.amount- COALESCE(payments.amount,0) ELSE 0 END) AS overdue,
      SUM(CASE WHEN due_on >= CURDATE() THEN transactions.amount-COALESCE(payments.amount,0) ELSE 0 END) AS outstanding
  FROM
      transactions
          LEFT JOIN
          payments ON transactions.id = payments.transaction_id
  WHERE
      due_on BETWEEN :due_on_start_date AND :due_on_end_date
  GROUP BY
      YEAR(due_on),MONTH(due_on)
  ORDER BY
      YEAR(due_on), MONTH(due_on))
 ) as report group by year,month order by year,month asc
", ['paid_on_start_date' => $startDate,'paid_on_end_date'=>$endDate,'due_on_start_date' => $startDate,'due_on_end_date'=>$endDate]);
        return $results;
    }

    public function getTransactionById(int $id): Transaction
    {
        return Transaction::find($id);
    }
}
