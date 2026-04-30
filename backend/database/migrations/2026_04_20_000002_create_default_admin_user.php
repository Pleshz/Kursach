<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $email = 'admin@carshare.local';

        $exists = DB::table('users')->where('email', $email)->exists();
        if ($exists) {
            return;
        }

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => $email,
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'admin@carshare.local')
            ->where('role', 'admin')
            ->delete();
    }
};

