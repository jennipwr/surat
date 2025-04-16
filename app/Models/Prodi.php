<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id_prodi', 'id_prodi');
    }
}
