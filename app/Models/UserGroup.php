<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    protected $guarded = ["group_id"];
    protected $primaryKey = "group_id";

    public function user()
    {
        return $this->hasMany(User::class, 'group_id');
    }
}
