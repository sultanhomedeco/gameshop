<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Promo;
use App\Models\TopupPackage;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gameshop.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Create operator user
        User::create([
            'name' => 'Operator',
            'email' => 'operator@gameshop.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'phone' => '081234567891',
        ]);

        // Create sample games
        $mobileLegends = Game::create([
            'name' => 'Mobile Legends',
            'description' => 'Top-up Diamond untuk Mobile Legends: Bang Bang',
            'currency_name' => 'Diamond',
            'is_active' => true,
        ]);

        $freeFire = Game::create([
            'name' => 'Free Fire',
            'description' => 'Top-up UC (Unknown Cash) untuk Free Fire',
            'currency_name' => 'UC',
            'is_active' => true,
        ]);

        $pubg = Game::create([
            'name' => 'PUBG Mobile',
            'description' => 'Top-up UC untuk PUBG Mobile',
            'currency_name' => 'UC',
            'is_active' => true,
        ]);

        $genshin = Game::create([
            'name' => 'Genshin Impact',
            'description' => 'Top-up Genesis Crystal untuk Genshin Impact',
            'currency_name' => 'Genesis Crystal',
            'is_active' => true,
        ]);

        // Create topup packages for Mobile Legends
        TopupPackage::create([
            'game_id' => $mobileLegends->id,
            'name' => '86 Diamond',
            'amount' => 86,
            'price' => 20000,
            'description' => '86 Diamond + 14 Bonus',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $mobileLegends->id,
            'name' => '172 Diamond',
            'amount' => 172,
            'price' => 40000,
            'description' => '172 Diamond + 28 Bonus',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $mobileLegends->id,
            'name' => '257 Diamond',
            'amount' => 257,
            'price' => 60000,
            'description' => '257 Diamond + 43 Bonus',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $mobileLegends->id,
            'name' => '344 Diamond',
            'amount' => 344,
            'price' => 80000,
            'description' => '344 Diamond + 56 Bonus',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $mobileLegends->id,
            'name' => '429 Diamond',
            'amount' => 429,
            'price' => 100000,
            'description' => '429 Diamond + 71 Bonus',
            'is_active' => true,
        ]);

        // Create topup packages for Free Fire
        TopupPackage::create([
            'game_id' => $freeFire->id,
            'name' => '100 UC',
            'amount' => 100,
            'price' => 15000,
            'description' => '100 UC',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $freeFire->id,
            'name' => '310 UC',
            'amount' => 310,
            'price' => 45000,
            'description' => '310 UC + 10 Bonus',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $freeFire->id,
            'name' => '520 UC',
            'amount' => 520,
            'price' => 75000,
            'description' => '520 UC + 20 Bonus',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $freeFire->id,
            'name' => '830 UC',
            'amount' => 830,
            'price' => 120000,
            'description' => '830 UC + 30 Bonus',
            'is_active' => true,
        ]);

        // Create topup packages for PUBG Mobile
        TopupPackage::create([
            'game_id' => $pubg->id,
            'name' => '60 UC',
            'amount' => 60,
            'price' => 10000,
            'description' => '60 UC',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $pubg->id,
            'name' => '325 UC',
            'amount' => 325,
            'price' => 50000,
            'description' => '325 UC + 25 Bonus',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $pubg->id,
            'name' => '660 UC',
            'amount' => 660,
            'price' => 100000,
            'description' => '660 UC + 60 Bonus',
            'is_active' => true,
        ]);

        // Create topup packages for Genshin Impact
        TopupPackage::create([
            'game_id' => $genshin->id,
            'name' => '60 Genesis Crystal',
            'amount' => 60,
            'price' => 15000,
            'description' => '60 Genesis Crystal',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $genshin->id,
            'name' => '300 Genesis Crystal',
            'amount' => 300,
            'price' => 75000,
            'description' => '300 Genesis Crystal + 30 Bonus',
            'is_active' => true,
        ]);

        TopupPackage::create([
            'game_id' => $genshin->id,
            'name' => '980 Genesis Crystal',
            'amount' => 980,
            'price' => 240000,
            'description' => '980 Genesis Crystal + 110 Bonus',
            'is_active' => true,
        ]);

        // Create sample promos
        Promo::create([
            'code' => 'WELCOME10',
            'name' => 'Welcome Bonus 10%',
            'description' => 'Diskon 10% untuk transaksi pertama Anda',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_amount' => 10000,
            'max_discount' => 50000,
            'usage_limit' => 1000,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        Promo::create([
            'code' => 'GAMING5000',
            'name' => 'Gaming Festival',
            'description' => 'Potongan langsung Rp 5.000 untuk semua game',
            'discount_type' => 'fixed',
            'discount_value' => 5000,
            'min_amount' => 25000,
            'usage_limit' => 500,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
        ]);

        Promo::create([
            'code' => 'BONUS100',
            'name' => 'Bonus 100 Diamond',
            'description' => 'Bonus 100 Diamond untuk Mobile Legends',
            'discount_type' => 'bonus',
            'discount_value' => 100,
            'min_amount' => 50000,
            'usage_limit' => 200,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addWeeks(2),
            'applicable_games' => [$mobileLegends->id],
        ]);
    }
}
