<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface {
    public function create(array $data) {
        return Payment::create($data);
    }
}
