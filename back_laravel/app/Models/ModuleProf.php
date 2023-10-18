<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleProf extends Model
{
    use HasFactory;
    protected $hidden = [
        "created_at",
        "updated_at",
    ];

    public function prof()
    {
        return $this->belongsTo(User::class, "prof_id");
    }
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function cours()
    {
        return $this->hasMany(Cours::class);
    }
}
