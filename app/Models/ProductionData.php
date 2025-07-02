<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionData extends Model
{
    protected $guarded = ['id'];
    protected $table = 'production_datas';

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
