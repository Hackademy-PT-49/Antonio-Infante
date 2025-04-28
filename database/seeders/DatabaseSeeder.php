<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crea l'utente di test esistente
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Crea l'utente admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@aulabpost.com',
            'password' => Hash::make('Password123!'),
        ]);

        // Chiama i seeder esistenti
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
