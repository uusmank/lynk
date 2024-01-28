<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payer_id',
        'due_on',
        'vat',
        'is_vat_inclusive',
        'status',
    ];
    public function payer() {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'transaction_id');
    }

    public function scopeForUser($query, User $user) {
        if ($user->isAdmin()) {
            return $query; // Admins can see all transactions
        }
        return $query->where('payer_id', $user->id);
    }

    /**
     * Get the transaction status.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            $this->calculateTransactionStatus(),
        );
    }

    public function calculateTransactionStatus(): string{

        $currentDateTime = Carbon::now();

        $totalPaidAmount = $this->payments->sum('amount');

        if ($currentDateTime > $this->attributes['due_on']) {
            return $totalPaidAmount >= $this->amount ? 'Paid' : 'Overdue';
        }

        return $totalPaidAmount >= $this->amount ? 'Paid' : 'Outstanding';
    }


}
