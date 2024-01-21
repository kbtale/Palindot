<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Subset;
use App\Models\Url;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('url_subsets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Subset::class, 'subset_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Url::class, 'url_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_subsets');
    }
};
