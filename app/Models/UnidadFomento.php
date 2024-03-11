<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadFomento extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'valor'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $table = 'unidad_fomento';
    protected $primaryKey = 'fecha';
    protected $keyType = 'date';
    public $incrementing = false;

}
