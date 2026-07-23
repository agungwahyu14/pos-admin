<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Kategori
        $categories = [
            'Cups' => ['Coffee', 'Non-Coffee'],
            'Food' => ['Pastry & Snacks', 'Add-ons']
        ];

        $categoryIds = [];
        $subCategoryIds = [];

        foreach ($categories as $catName => $subCats) {
            $category = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'description' => 'Kategori utama ' . $catName,
            ]);
            $categoryIds[$catName] = $category->id;

            foreach ($subCats as $subCatName) {
                $subCategory = \App\Models\SubCategory::create([
                    'category_id' => $category->id,
                    'name' => $subCatName,
                    'slug' => Str::slug($subCatName),
                ]);
                $subCategoryIds[$subCatName] = $subCategory->id;
            }
        }

        // 2. Buat Produk
        $products = [
            // Coffee
            [
                'category_id' => $categoryIds['Cups'],
                'sub_category_id' => $subCategoryIds['Coffee'],
                'name' => 'Americano (Hot/Ice)',
                'sku' => 'COF-AMR-001',
                'price' => 20000,
                'stock' => 100,
            ],  
            [
                'category_id' => $categoryIds['Cups'],
                'sub_category_id' => $subCategoryIds['Coffee'],
                'name' => 'Cafe Latte (Hot/Ice)',
                'sku' => 'COF-LAT-002',
                'price' => 25000,
                'stock' => 100,
            ],
            [
                'category_id' => $categoryIds['Cups'],
                'sub_category_id' => $subCategoryIds['Coffee'],
                'name' => 'Cappuccino',
                'sku' => 'COF-CAP-003',
                'price' => 25000,
                'stock' => 50,
            ],
            [
                'category_id' => $categoryIds['Cups'],
                'sub_category_id' => $subCategoryIds['Coffee'],
                'name' => 'Caramel Macchiato',
                'sku' => 'COF-MAC-004',
                'price' => 30000,
                'stock' => 50,
            ],

            // Non-Coffee
            [
                'category_id' => $categoryIds['Cups'],
                'sub_category_id' => $subCategoryIds['Non-Coffee'],
                'name' => 'Matcha Latte',
                'sku' => 'NCO-MAT-001',
                'price' => 28000,
                'stock' => 80,
            ],
            [
                'category_id' => $categoryIds['Cups'],
                'sub_category_id' => $subCategoryIds['Non-Coffee'],
                'name' => 'Red Velvet Latte',
                'sku' => 'NCO-RED-002',
                'price' => 28000,
                'stock' => 80,
            ],
            [
                'category_id' => $categoryIds['Cups'],
                'sub_category_id' => $subCategoryIds['Non-Coffee'],
                'name' => 'Lychee Tea',
                'sku' => 'NCO-LYC-003',
                'price' => 22000,
                'stock' => 100,
            ],

            // Pastry & Snacks
            [
                'category_id' => $categoryIds['Food'],
                'sub_category_id' => $subCategoryIds['Pastry & Snacks'],
                'name' => 'Butter Croissant',
                'sku' => 'PST-CRO-001',
                'price' => 18000,
                'stock' => 20,
            ],
            [
                'category_id' => $categoryIds['Food'],
                'sub_category_id' => $subCategoryIds['Pastry & Snacks'],
                'name' => 'Chocolate Brownie',
                'sku' => 'PST-BRO-002',
                'price' => 25000,
                'stock' => 15,
            ],
            [
                'category_id' => $categoryIds['Food'],
                'sub_category_id' => $subCategoryIds['Pastry & Snacks'],
                'name' => 'French Fries',
                'sku' => 'SNC-FF-001',
                'price' => 20000,
                'stock' => 50,
            ],

            // Add-ons
            [
                'category_id' => $categoryIds['Food'],
                'sub_category_id' => $subCategoryIds['Add-ons'],
                'name' => 'Extra Espresso Shot',
                'sku' => 'ADD-ESP-001',
                'price' => 5000,
                'stock' => 1000,
            ],
            [
                'category_id' => $categoryIds['Food'],
                'sub_category_id' => $subCategoryIds['Add-ons'],
                'name' => 'Oat Milk Upgrade',
                'sku' => 'ADD-OAT-002',
                'price' => 8000,
                'stock' => 50,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
