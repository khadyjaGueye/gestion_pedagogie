<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function profs()
    {
        return $this->belongsToMany(User::class,'module_profs','module_id','prof_id');
    }

    // public function getProfByIdModuleProf($id){
    //     $prof = Module::find($id)->pr;
    // }

    public function prof()
    {
        return $this->belongs(User::class,'prof_id');
    }
}
