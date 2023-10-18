<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function cour(){
        return $this->belongsTo(Cour::class);
    }
    public function user(){
        return $this->belongsTo(User::class,'prof_id','users');
    }
    public function salle(){
        return $this->belongsTo(Salle::class);
    }
}
