<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Validation\ValidationException;

class PaymentsController extends Controller {
    private $paymentRepository;
    private $transactionRepository;

    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function store(StorePaymentRequest $request) {
        try {
            $validatedData = $request->validated();
            $transactionId = $request->get('transaction_id');
            $transaction = $this->transactionRepository->getTransactionById($transactionId);

            // Create the payment
            $paymentData = [
                'transaction_id' => $transaction->id,
                'amount' => $validatedData['amount'],
                'paid_on' => $validatedData['paid_on'],
                'details' => isset($validatedData['details'])?$validatedData['details']:Null,
            ];
            $payment = $this->paymentRepository->create($paymentData);
            $this->transactionRepository->updateStatus($transactionId);

            return response()->json(['message' => 'Payment recorded successfully', 'data' => $payment], 201);
        } catch (ValidationException $e) {
            // Validation failed, handle the response
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}
