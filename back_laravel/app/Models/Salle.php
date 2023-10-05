<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function isAvailable($date, $heureDebut, $heureFin)
    {
        // Récupérer les autres sessions prévues dans cette salle
        $otherSessions = Session::where('salle_id', $this->id)
            ->whereDate('dateCours', $date)
            ->get();

        foreach ($otherSessions as $session) {
            // Si une autre session chevauche cette plage horaire
            if ($session->heureDebut < $heureFin && $session->heureFin > $heureDebut) {
                // La salle n'est pas disponible
                return false;
            }
        }
        // La salle est disponible
        return true;
    }
}
