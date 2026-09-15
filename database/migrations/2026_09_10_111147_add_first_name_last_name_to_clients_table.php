<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
        });

        $clients = DB::table('clients')->get();

        foreach ($clients as $client) {
            $name = trim($client->name);

            if ($name === '') {
                continue;
            }

            $parts = preg_split('/\s+/', $name, 2);

            $firstName = $parts[0];
            $lastName = $parts[1] ?? '';

            DB::table('clients')
                ->where('id', $client->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
            ]);
        });
    }
};