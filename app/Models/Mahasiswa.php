<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nrp';
    protected $fillable = ['nrp', 'nama', 'alamat', 'semester', 'email', 'prodi', 'prodi_id_prodi'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id_prodi', 'id_prodi');
    }
}
