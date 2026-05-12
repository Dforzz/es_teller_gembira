<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Es Teler Gembira',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $cat1 = Category::create(['name' => 'Es Teler', 'slug' => 'es-teler']);
        $cat2 = Category::create(['name' => 'Es Campur', 'slug' => 'es-campur']);

        Menu::create([
            'category_id' => $cat1->id,
            'name' => 'Es Teler Original',
            'description' => 'Alpukat, nangka, kelapa muda, jelly, susu creamy.',
            'price' => 12000,
            'image' => 'https://images.unsplash.com/photo-1570197788417-0e82375c9371?auto=format&fit=crop&w=400&q=80',
        ]);
        Menu::create([
            'category_id' => $cat1->id,
            'name' => 'Es Teler Alpukat Premium',
            'description' => 'Extra alpukat mentega super creamy dengan porsi besar.',
            'price' => 18000,
            'image' => 'https://images.unsplash.com/photo-1589733955941-5eeaf752f6dd?auto=format&fit=crop&w=400&q=80',
        ]);
        Menu::create([
            'category_id' => $cat1->id,
            'name' => 'Es Teler Jumbo Family',
            'description' => 'Porsi super besar untuk sharing bersama keluarga.',
            'price' => 35000,
            'image' => 'https://images.unsplash.com/photo-1638176066666-ffb2f013c7dd?auto=format&fit=crop&w=400&q=80',
        ]);
        Menu::create([
            'category_id' => $cat2->id,
            'name' => 'Es Campur Spesial',
            'description' => 'Cincau, tape, mutiara, kolang-kaling, sirup merah.',
            'price' => 15000,
            'image' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=400&q=80',
        ]);
    }
}
