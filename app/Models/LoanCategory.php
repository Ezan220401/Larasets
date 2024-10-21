<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCategory extends Model
{
    use HasFactory;
    protected $guarded=['category_id'];
    protected $primaryKey="category_id";
    protected $table = "loan_categorys";

    public function loan()
    {
        return $this->hasMany(Loan::class);
    }
}
