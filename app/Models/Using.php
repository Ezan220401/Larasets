<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Using extends Model
{
    use HasFactory;
    protected $primaryKey = 'using_id';
    protected $table = 'usings';
    protected $guarded =['using_id'];

    public function loan(){
       return $this->belongsTo(Loan::class, 'using_id', 'using_id');
    }
}
