<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'prodi';
    protected $primaryKey  = 'id_prodi';

    protected $fillable = [
        'id_prodi',
        'nama_prodi',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id_prodi', 'id_prodi');
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'prodi_id_prodi', 'id_prodi');
    }
}
