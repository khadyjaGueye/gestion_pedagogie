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

    public function isAvailable($date, $heureDebut, $heureFin)
    {
        // Récupérer les autres sessions où ce prof intervient
        $otherSessions = Session::where('prof_id', $this->id)
            ->whereDate('dateCours', $date)
            ->get();

        foreach ($otherSessions as $session) {

            // Si une autre session chevauche cette plage horaire
            if ($session->heureDebut < $heureFin && $session->heureFin > $heureDebut) {
                // Le professeur n'est pas disponible
                return false;
            }
        }
        // Le professeur est disponible
        return true;
    }
}
