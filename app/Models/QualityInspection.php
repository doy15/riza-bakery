<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityInspection extends Model
{
    protected $guarded = ['id'];
    protected $table = 'quality_inspections';

    // QualityInspection.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function produksi()
    {
        return $this->belongsTo(ProductionData::class, 'production_data_id');
    }

}
