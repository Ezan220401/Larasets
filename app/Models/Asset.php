<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $primaryKey = 'asset_id';
    protected $guarded = ['asset_id'];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function loans(){
        // return $$this->belongsTo(Loan::class, 'loan_id');
        return $this->hasMany(Loan::class, 'asset_id', 'asset_id');
    }
}
