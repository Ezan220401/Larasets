<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded=['category_id'];
    protected $primaryKey="category_id";
    protected $table = "asset_categorys";

    public function asset()
    {
        return $this->hasMany(Asset::class);
    }
}
