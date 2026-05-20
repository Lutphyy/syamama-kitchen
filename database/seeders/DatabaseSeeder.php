<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin Syamama',
            'email' => 'admin@syamama.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Create test user
        User::create([
            'name' => 'Pembeli Test',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567891',
        ]);

        // Categories
        $categories = [
            ['name' => 'Kue Kering', 'slug' => 'kue-kering', 'description' => 'Aneka kue kering renyah dan lezat', 'icon' => '🍪'],
            ['name' => 'Kue Basah', 'slug' => 'kue-basah', 'description' => 'Kue basah tradisional yang nikmat', 'icon' => '🍰'],
            ['name' => 'Roti & Pastry', 'slug' => 'roti-pastry', 'description' => 'Roti dan pastry segar setiap hari', 'icon' => '🥐'],
            ['name' => 'Minuman', 'slug' => 'minuman', 'description' => 'Minuman segar dan hangat', 'icon' => '🥤'],
            ['name' => 'Makanan Berat', 'slug' => 'makanan-berat', 'description' => 'Menu makanan utama yang mengenyangkan', 'icon' => '🍱'],
            ['name' => 'Sambal & Bumbu', 'slug' => 'sambal-bumbu', 'description' => 'Sambal dan bumbu khas rumahan', 'icon' => '🌶️'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Products
        $products = [
            // Kue Kering
            ['category_id' => 1, 'name' => 'Nastar Premium', 'slug' => 'nastar-premium', 'description' => 'Nastar premium dengan selai nanas asli, lembut dan lumer di mulut. Dibuat dengan bahan berkualitas tinggi.', 'price' => 85000, 'stock' => 50, 'is_active' => true],
            ['category_id' => 1, 'name' => 'Kastengel Keju', 'slug' => 'kastengel-keju', 'description' => 'Kastengel dengan keju edam pilihan, gurih dan renyah sempurna.', 'price' => 90000, 'stock' => 40, 'is_active' => true],
            ['category_id' => 1, 'name' => 'Putri Salju', 'slug' => 'putri-salju', 'description' => 'Kue putri salju yang lembut dengan taburan gula halus, meleleh di mulut.', 'price' => 75000, 'stock' => 35, 'is_active' => true],
            ['category_id' => 1, 'name' => 'Lidah Kucing Coklat', 'slug' => 'lidah-kucing-coklat', 'description' => 'Lidah kucing renyah dengan coklat premium, cocok untuk cemilan dan hadiah.', 'price' => 70000, 'stock' => 45, 'is_active' => true],

            // Kue Basah
            ['category_id' => 2, 'name' => 'Brownies Panggang', 'slug' => 'brownies-panggang', 'description' => 'Brownies panggang dengan coklat belgian, fudgy dan rich. Topping kacang almond.', 'price' => 55000, 'stock' => 20, 'is_active' => true],
            ['category_id' => 2, 'name' => 'Bolu Pandan', 'slug' => 'bolu-pandan', 'description' => 'Bolu pandan lembut dengan aroma pandan asli, cocok untuk segala acara.', 'price' => 45000, 'stock' => 15, 'is_active' => true],
            ['category_id' => 2, 'name' => 'Lapis Legit', 'slug' => 'lapis-legit', 'description' => 'Lapis legit spesial dengan rempah pilihan, dibuat dengan resep turun temurun.', 'price' => 150000, 'stock' => 10, 'is_active' => true],
            ['category_id' => 2, 'name' => 'Kue Lumpur', 'slug' => 'kue-lumpur', 'description' => 'Kue lumpur kentang yang lembut dan manis, topping kismis. Per box isi 12.', 'price' => 35000, 'stock' => 25, 'is_active' => true],

            // Roti & Pastry
            ['category_id' => 3, 'name' => 'Roti Sobek Coklat', 'slug' => 'roti-sobek-coklat', 'description' => 'Roti sobek lembut dengan isian coklat melimpah, cocok untuk sarapan.', 'price' => 35000, 'stock' => 30, 'is_active' => true],
            ['category_id' => 3, 'name' => 'Croissant Butter', 'slug' => 'croissant-butter', 'description' => 'Croissant berlapis-lapis dengan butter premium, renyah di luar lembut di dalam. Isi 3pcs.', 'price' => 45000, 'stock' => 20, 'is_active' => true],

            // Minuman
            ['category_id' => 4, 'name' => 'Sirup Markisa', 'slug' => 'sirup-markisa', 'description' => 'Sirup markisa homemade, segar dan asli tanpa pengawet. Botol 500ml.', 'price' => 35000, 'stock' => 40, 'is_active' => true],
            ['category_id' => 4, 'name' => 'Wedang Jahe', 'slug' => 'wedang-jahe', 'description' => 'Wedang jahe merah instan, hangat dan menyehatkan. Isi 10 sachet.', 'price' => 25000, 'stock' => 50, 'is_active' => true],

            // Makanan Berat
            ['category_id' => 5, 'name' => 'Rendang Daging', 'slug' => 'rendang-daging', 'description' => 'Rendang daging sapi asli Padang, empuk dan kaya rempah. Per pack 500gr.', 'price' => 120000, 'stock' => 15, 'is_active' => true],
            ['category_id' => 5, 'name' => 'Ayam Geprek Frozen', 'slug' => 'ayam-geprek-frozen', 'description' => 'Ayam geprek frozen siap goreng dengan sambal khas. Isi 5 potong.', 'price' => 65000, 'stock' => 25, 'is_active' => true],

            // Sambal & Bumbu
            ['category_id' => 6, 'name' => 'Sambal Bawang', 'slug' => 'sambal-bawang', 'description' => 'Sambal bawang homemade, gurih dan pedas pas. Jar 250ml.', 'price' => 30000, 'stock' => 60, 'is_active' => true],
            ['category_id' => 6, 'name' => 'Bumbu Nasi Goreng', 'slug' => 'bumbu-nasi-goreng', 'description' => 'Bumbu nasi goreng instan homemade, tinggal tumis! Isi 5 sachet.', 'price' => 20000, 'stock' => 50, 'is_active' => true],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }
    }
}
