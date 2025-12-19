<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory, SoftDeletes;

    public function kategori()
    {
        return $this->belongsToMany(
            Kategori::class,
            'kategori_item',
            'id_item',     // FK pivot ke master_items
            'id_kategori'  // FK pivot ke kategoris
        );
    }
}
