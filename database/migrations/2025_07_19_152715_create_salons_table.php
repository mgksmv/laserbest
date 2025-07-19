<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salons', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        $salons = [
            'da784dce-d63d-11ee-9a91-0050568319a1' => 'Ирчи Казака 36',
            '33893788-d61c-11ee-9a91-0050568319a1' => 'Шамиля 24Б',
            '303aec64-d6ff-11ee-9a91-0050568319a1' => 'Джикаева 4',
            '62679a20-d632-11ee-9a91-0050568319a1' => 'Гамзатова 115А',
            'c5211860-d62f-11ee-9a91-0050568319a1' => 'Офис',
            '9a5784d1-3a01-11ef-a31f-74563c9c055c' => 'Шамиля 70В',
            '914c9b74-d475-11ee-838f-0050568319a1' => 'Коркмасова 27',
        ];

        foreach ($salons as $id => $name) {
            DB::connection($this->getConnection())
                ->table('salons')
                ->insert([
                    'id' => $id,
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salons');
    }
};
