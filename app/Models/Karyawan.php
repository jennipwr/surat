<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $primaryKey = 'nik';
    protected $fillable = ['nik', 'nama', 'jabatan', 'prodi_id_prodi'];
    protected $keyType = 'string';
    public $incrementing = false;
}
