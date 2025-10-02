<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'applicant_full_name','email','phone','date_of_birth',
        'vehicle_type','vehicle_make','vehicle_model',
        'purchase_price','deposit_amount','term_months',
        'status','consent_to_credit_check','submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'date_of_birth' => 'date',
        'consent_to_credit_check' => 'boolean',
    ];

    public function getLoanAmountAttribute(): float
    {
        return (float)$this->purchase_price - (float)$this->deposit_amount;
    }

    public function scopeSearch($q, ?string $term) {
        if (!$term) return;
        $q->where(function($qq) use ($term) {
            $qq->where('applicant_full_name','like',"%$term%")
            ->orWhere('email','like',"%$term%")
            ->orWhere('vehicle_make','like',"%$term%")
            ->orWhere('vehicle_model','like',"%$term%");
        });
    }
    public function scopeVehicleType($q, ?string $type){ if($type) $q->where('vehicle_type',$type); }
    public function scopeStatus($q, ?string $status){ if($status) $q->where('status',$status); }
}
