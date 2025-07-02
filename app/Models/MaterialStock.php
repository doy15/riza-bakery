<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialStock extends Model
{
    protected $guarded = ['id'];
    protected $table = 'material_stocks';

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
