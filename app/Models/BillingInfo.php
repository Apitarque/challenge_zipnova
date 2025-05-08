<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingInfo extends Model
{
    use HasFactory;
    protected $fillable = ["tax_id","company_name","address_id"];

    public function address() {
        return $this->belongsTo(Address::class);
    }
}
