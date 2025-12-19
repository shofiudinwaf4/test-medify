<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->belongsToMany(
            MasterItem::class,
            'kategori_item',
            'id_kategori', // FK pivot ke kategoris
            'id_item'      // FK pivot ke master_items
        );
    }
}
