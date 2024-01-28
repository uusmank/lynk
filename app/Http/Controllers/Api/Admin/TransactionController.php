<?php

namespace app\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinancialReportRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Http\Request;

class TransactionController extends Controller {
    private $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository) {
        $this->transactionRepository = $transactionRepository;
    }

    public function store(StoreTransactionRequest $request) {
        $validatedData = $request->validated();

        $transactionData = [
            'amount' => $validatedData['amount'],
            'payer_id' =>  $validatedData['payer_id'],
            'due_on' =>  $validatedData['due_on'],
            'vat' =>  $validatedData['vat'],
            'is_vat_inclusive' =>  $validatedData['is_vat_inclusive'],
        ];
        $transaction = $this->transactionRepository->create($transactionData);

        return response()->json(['message' => 'Transaction created successfully', 'data' => $transaction], 201);
    }

    public function index(Request $request) {
        $user = $request->user();

        if ($user->user_type === 'admin') {
            $transactions = $this->transactionRepository->getAll();
        } else {
            $transactions = $this->transactionRepository->userTransactions($user->id);
        }
        return response()->json($transactions);
    }

    public function financialReports(FinancialReportRequest $request) {
        $validatedData = $request->validated();
        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];
        $reports = $this->transactionRepository->generateMonthlyReports($startDate, $endDate);
        return response()->json($reports);
    }

}
