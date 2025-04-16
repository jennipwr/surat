<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';
    protected $primaryKey  = 'id_surat';
    protected $fillable = [
      'id_surat',
      'jenis_surat',
      'file',
      'status',
      'keperluan_aktif',
      'keperluan_hasil_studi',
      'tujuan',
      'topik',
      'tanggal_pengajuan',
      'tanggal_persetujuan',
      'mahasiswa_nrp',
      'mahasiswa_nama',
      'alamat',
      'semester',
      'kode_mk',
      'nama_mk',
      'catatan',
    ];
    protected $keyType = 'integer';
    public $incrementing = true;

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nrp', 'nrp');
    }
}
