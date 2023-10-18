<?php

use App\Models\Cour;
use App\Models\Salle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->date("dateCours");
            $table->time('hDedut');
            $table->time('hFin');
            $table->time('duree');
            $table->boolean('etat')->default(true);
            $table->foreignIdFor(Salle::class)->constrained();
            $table->foreignIdFor(Cour::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
