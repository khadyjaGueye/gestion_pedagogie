<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
    ];
    public function module_prof()
    {
        return $this->belongsTo(ModuleProf::class);
    }
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
