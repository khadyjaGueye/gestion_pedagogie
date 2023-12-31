<?php

use App\Models\Classe;
use App\Models\Cour;
use App\Models\Session;
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
        Schema::create('cour_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cour::class)->constrained();
            $table->foreignIdFor(Classe::class)->constrained();
            $table->foreignId("etudiant_id")->constrained("users");
            $table->foreignIdFor(Session::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cour_classes');
    }
};
