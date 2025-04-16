<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $primaryKey = 'nik';
    protected $fillable = ['nik', 'nama', 'jabatan', 'prodi_id_prodi', 'email'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id_prodi', 'id_prodi');
    }
}
