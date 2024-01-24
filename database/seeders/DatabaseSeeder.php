<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Product;
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
        User::create([
            "name" => "nadiyah",
            "role" => "siswa",
            "email" => "nadiyah@gmail.com",
            "password" => Hash::make("nadiyah")
        ]);
        User::create([
            "name" => "kantin",
            "role" => "kantin",
            "email" => "kantin@gmail.com",
            "password" => Hash::make("kantin123")
        ]);
        User::create([
            "name" => "bank",
            "role" => "bank",
            "email" => "bank@gmail.com",
            "password" => Hash::make("bank123")
        ]);

        Category::create([
            "name"=> "Makanan",
        ]);
        Category::create([
            "name"=> "Minuman",
        ]);
        Category::create([
            "name"=> "Cemilan",
        ]);

        Product::create([
            "name"=> "Bakso",
            "price" => 20000,
            "stock" => 30,
            "photo"=> "images/bakso.jpg",
            "description" => "Bakso Daging",
            "category_id"=> "1",
        ]);
        Product::create([
            "name"=> "Boba",
            "price" => 15000,
            "stock" => 50,
            "photo"=> "images/xiboba.jpg",
            "description" => "Xiboba",
            "category_id"=> "2",
        ]);
        Product::create([
            "name"=> "Risol",
            "price" => 10000,
            "stock" => 30,
            "photo"=> "images/risol.jpg",
            "description"=> "Risoles",
            "category_id"=> "2",
        ]);
        Product::create([
            "name"=> "Tteokbokki",
            "price" => 15000,
            "stock" => 30,
            "photo"=> "images/topoki.jpg",
            "description"=> "Korea Pedas",
            "category_id"=> "1",
        ]);
        Product::create([
            "name"=> "Rabbokki",
            "price" => 29000,
            "stock" => 30,
            "photo"=> "images/raboki.jpeg",
            "description"=> "Korea Pedas",
            "category_id"=> "1",
        ]);
        Product::create([
            "name"=> "Uyyu",
            "price" => 10000,
            "stock" => 50,
            "photo"=> "images/puyu.jpg",
            "description"=> "Susu Pisang",
            "category_id"=> "2",
        ]);
    }
}
