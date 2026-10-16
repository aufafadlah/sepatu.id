<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $shoeImages = [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560769629-975ec94e6a86?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1575537302964-96cd47c06b1b?auto=format&fit=crop&w=800&q=80',
        ];

        $products = [
            ['name' => 'Air Runner X', 'category' => 'Running', 'price' => 850000, 'description' => 'Sepatu lari ringan dengan bantalan empuk, cocok untuk pemakaian harian maupun lari jarak jauh.', 'image' => $shoeImages[0]],
            ['name' => 'Zoom Casual', 'category' => 'Casual', 'price' => 990000, 'description' => 'Desain kasual serbaguna, nyaman dipakai sehari-hari.', 'image' => $shoeImages[1]],
            ['name' => 'Court Star', 'category' => 'Basketball', 'price' => 720000, 'description' => 'Sepatu basket dengan grip kuat dan bantalan pergelangan kaki.', 'image' => $shoeImages[2]],
            ['name' => 'Street Hi', 'category' => 'Lifestyle', 'price' => 830000, 'description' => 'Gaya jalanan dengan siluet tinggi, cocok untuk tampil kasual.', 'image' => $shoeImages[3]],
            ['name' => 'Ultraboost Flow', 'category' => 'Running', 'price' => 1450000, 'description' => 'Teknologi bantalan responsif untuk performa lari maksimal.', 'image' => $shoeImages[4]],
            ['name' => 'Superstar Classic', 'category' => 'Lifestyle', 'price' => 1100000, 'description' => 'Model klasik dengan detail shell-toe yang ikonik.', 'image' => $shoeImages[5]],
            ['name' => 'Trainer Pro', 'category' => 'Training', 'price' => 780000, 'description' => 'Dirancang untuk latihan intensitas tinggi di gym.', 'image' => $shoeImages[6]],
            ['name' => 'Flex Motion', 'category' => 'Training', 'price' => 690000, 'description' => 'Fleksibilitas tinggi untuk berbagai jenis latihan.', 'image' => $shoeImages[0]],
            ['name' => 'Retro Walk', 'category' => 'Casual', 'price' => 610000, 'description' => 'Gaya retro dengan kenyamanan modern untuk jalan santai.', 'image' => $shoeImages[1]],
            ['name' => 'Slam Dunk Elite', 'category' => 'Basketball', 'price' => 1250000, 'description' => 'Performa tinggi untuk pemain basket kompetitif.', 'image' => $shoeImages[2]],
            ['name' => 'City Sneaker', 'category' => 'Lifestyle', 'price' => 590000, 'description' => 'Sneaker minimalis untuk aktivitas perkotaan.', 'image' => $shoeImages[3]],
            ['name' => 'Marathon Light', 'category' => 'Running', 'price' => 970000, 'description' => 'Super ringan, didesain khusus untuk lari maraton.', 'image' => $shoeImages[4]],
            ['name' => 'Core Trainer', 'category' => 'Training', 'price' => 740000, 'description' => 'Stabilitas maksimal untuk latihan angkat beban.', 'image' => $shoeImages[5]],
            ['name' => 'Vibe Casual', 'category' => 'Casual', 'price' => 640000, 'description' => 'Kenyamanan sehari-hari dengan desain simpel.', 'image' => $shoeImages[6]],
            ['name' => 'Hoops Legend', 'category' => 'Basketball', 'price' => 1050000, 'description' => 'Edisi legend dengan cushioning premium.', 'image' => $shoeImages[0]],
        ];

        $sizes = ['39', '40', '41', '42', '43'];

        foreach ($products as $item) {
            $category = $categories[$item['category']] ?? null;

            if (! $category) {
                continue;
            }

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                ]
            );

            // Pastikan varian ukuran tersedia jika belum ada
            if ($product->variants()->count() === 0) {
                $variantSizes = collect($sizes)->random(rand(3, 5))->values();

                foreach ($variantSizes as $size) {
                    $product->variants()->create([
                        'size' => $size,
                        'stock' => rand(1, 30),
                    ]);
                }
            }
        }
    }
}