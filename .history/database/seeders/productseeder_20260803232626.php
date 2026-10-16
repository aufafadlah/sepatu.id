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

        $products = [
            ['name' => 'Air Runner X', 'category' => 'Running', 'price' => 850000, 'description' => 'Sepatu lari ringan dengan bantalan empuk, cocok untuk pemakaian harian maupun lari jarak jauh.'],
            ['name' => 'Zoom Casual', 'category' => 'Casual', 'price' => 990000, 'description' => 'Desain kasual serbaguna, nyaman dipakai sehari-hari.'],
            ['name' => 'Court Star', 'category' => 'Basketball', 'price' => 720000, 'description' => 'Sepatu basket dengan grip kuat dan bantalan pergelangan kaki.'],
            ['name' => 'Street Hi', 'category' => 'Lifestyle', 'price' => 830000, 'description' => 'Gaya jalanan dengan siluet tinggi, cocok untuk tampil kasual.'],
            ['name' => 'Ultraboost Flow', 'category' => 'Running', 'price' => 1450000, 'description' => 'Teknologi bantalan responsif untuk performa lari maksimal.'],
            ['name' => 'Superstar Classic', 'category' => 'Lifestyle', 'price' => 1100000, 'description' => 'Model klasik dengan detail shell-toe yang ikonik.'],
            ['name' => 'Trainer Pro', 'category' => 'Training', 'price' => 780000, 'description' => 'Dirancang untuk latihan intensitas tinggi di gym.'],
            ['name' => 'Flex Motion', 'category' => 'Training', 'price' => 690000, 'description' => 'Fleksibilitas tinggi untuk berbagai jenis latihan.'],
            ['name' => 'Retro Walk', 'category' => 'Casual', 'price' => 610000, 'description' => 'Gaya retro dengan kenyamanan modern untuk jalan santai.'],
            ['name' => 'Slam Dunk Elite', 'category' => 'Basketball', 'price' => 1250000, 'description' => 'Performa tinggi untuk pemain basket kompetitif.'],
            ['name' => 'City Sneaker', 'category' => 'Lifestyle', 'price' => 590000, 'description' => 'Sneaker minimalis untuk aktivitas perkotaan.'],
            ['name' => 'Marathon Light', 'category' => 'Running', 'price' => 970000, 'description' => 'Super ringan, didesain khusus untuk lari maraton.'],
            ['name' => 'Core Trainer', 'category' => 'Training', 'price' => 740000, 'description' => 'Stabilitas maksimal untuk latihan angkat beban.'],
            ['name' => 'Vibe Casual', 'category' => 'Casual', 'price' => 640000, 'description' => 'Kenyamanan sehari-hari dengan desain simpel.'],
            ['name' => 'Hoops Legend', 'category' => 'Basketball', 'price' => 1050000, 'description' => 'Edisi legend dengan cushioning premium.'],
        ];

        $sizes = ['39', '40', '41', '42', '43'];

        foreach ($products as $item) {
            $category = $categories[$item['category']] ?? null;

            if (! $category) {
                continue;
            }

            $product = Product::create([
                'category_id' => $category->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'],
                'price' => $item['price'],
                'image' => null, // ganti dengan path gambar asli nanti
            ]);

            // Buat 3-5 varian ukuran acak untuk tiap produk, sebagian sengaja
            // dibuat stok 0 supaya bisa testing tampilan "stok habis"
            $variantSizes = collect($sizes)->random(rand(3, 5))->values();

            foreach ($variantSizes as $size) {
                $product->variants()->create([
                    'size' => $size,
                    'stock' => rand(0, 1) === 0 ? 0 : rand(1, 30),
                ]);
            }
        }
    }
}