<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returning extends Model
{
    use HasFactory;
    protected $primaryKey = 'return_id';
    protected $table = 'returnings';
    protected $guarded = ['return_id'];

    public function loan(){
        return $this->belongsTo(Loan::class, 'return_id', 'return_id');
        
    }
}
