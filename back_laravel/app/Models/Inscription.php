<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function etudiant()
    {
        return $this->belongsTo(User::class, "etudiant_id");
    }
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
}
