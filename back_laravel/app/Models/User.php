<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        "delete_at",
        "deleted_at",
        "created_at",
        "updated_at",
        "email_verified_at"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    function isAvailable($date, $heureDebut, $heureFin)
    {
        // Récupérer les autres sessions
        $sessions = Session::whereDate('dateCours', $date)->get();
        foreach ($sessions as $session) {
            // Récupérer cours et module_prof
            $cours = $session->cours;
            $moduleProf = $cours->module_prof;
            if ($moduleProf->prof_id == $this->id) {
                // Vérifier chevauchement directement
                if ($this->checkOverlap($session, $heureDebut, $heureFin)) {
                    return false;
                }
            }
        }
        return true;
    }
    function checkOverlap($session1, $heureDebut2, $heureFin2)
    {
        if ($session1->heureDebut < $heureFin2 && $session1->heureFin > $heureDebut2) {
            return true;
        }
        return false;
    }
}
