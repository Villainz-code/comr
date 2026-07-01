<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin Sinestesia.co',
            'email' => 'admin@sinestesia.co',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081200000000',
            'address' => 'Jl. Admin No. 1, Jakarta',
        ]);

        // 5 Customer dummy
        $customers = [
            ['name' => 'Budi Santoso', 'email' => 'budi@customer.com', 'phone' => '081211111111', 'address' => 'Jl. Merdeka No. 10, Bandung'],
            ['name' => 'Siti Rahma', 'email' => 'siti@customer.com', 'phone' => '081222222222', 'address' => 'Jl. Sudirman No. 5, Surabaya'],
            ['name' => 'Andi Wijaya', 'email' => 'andi@customer.com', 'phone' => '081233333333', 'address' => 'Jl. Gatot Subroto No. 3, Yogyakarta'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@customer.com', 'phone' => '081244444444', 'address' => 'Jl. Diponegoro No. 7, Semarang'],
            ['name' => 'Reza Putra', 'email' => 'reza@customer.com', 'phone' => '081255555555', 'address' => 'Jl. Ahmad Yani No. 12, Medan'],
        ];

        foreach ($customers as $data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]);
        }

        // Categories
        $categories = [
            ['name' => 'T-Shirt', 'description' => 'Kaos berkualitas tinggi dengan berbagai desain modern'],
            ['name' => 'Hoodie', 'description' => 'Hoodie nyaman dan stylish untuk berbagai kesempatan'],
            ['name' => 'Jacket', 'description' => 'Jaket premium dengan bahan terbaik'],
            ['name' => 'Pants', 'description' => 'Celana panjang kasual dan formal'],
            ['name' => 'Accessories', 'description' => 'Aksesoris pelengkap penampilan Anda'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = Category::create($cat);
        }

        // Products
        $products = [
            [
                'category_id' => $categoryModels[0]->id,
                'name' => 'Sinestesia.co Classic Black Tee',
                'description' => 'T-shirt hitam klasik dengan logo Sinestesia.co, bahan cotton combed 30s, adem dan nyaman.',
                'price' => 185000,
                'stock' => 50,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[0]->id,
                'name' => 'Sinestesia.co Graphic White Tee',
                'description' => 'T-shirt putih dengan grafis eksklusif Sinestesia.co. Bahan premium soft cotton.',
                'price' => 210000,
                'stock' => 35,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[1]->id,
                'name' => 'Sinestesia.co Essential Hoodie Black',
                'description' => 'Hoodie hitam esensial dengan bahan fleece tebal, cocok untuk cuaca dingin.',
                'price' => 425000,
                'stock' => 20,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[1]->id,
                'name' => 'Sinestesia.co Oversized Hoodie Grey',
                'description' => 'Hoodie oversized abu-abu dengan cut yang relaxed fit dan nyaman dipakai seharian.',
                'price' => 480000,
                'stock' => 15,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[2]->id,
                'name' => 'Sinestesia.co Varsity Jacket',
                'description' => 'Jaket varsity dengan detail bordir Sinestesia.co, bahan canvas berkualitas tinggi.',
                'price' => 750000,
                'stock' => 10,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[2]->id,
                'name' => 'Sinestesia.co Bomber Jacket',
                'description' => 'Bomber jacket minimalist dengan bahan nylon water-resistant.',
                'price' => 680000,
                'stock' => 8,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[3]->id,
                'name' => 'Sinestesia.co Cargo Pants Black',
                'description' => 'Celana cargo hitam dengan banyak kantong fungsional, bahan ripstop.',
                'price' => 395000,
                'stock' => 25,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[3]->id,
                'name' => 'Sinestesia.co Jogger Pants',
                'description' => 'Jogger pants kasual dengan bahan cotton fleece, nyaman untuk aktivitas sehari-hari.',
                'price' => 285000,
                'stock' => 30,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[4]->id,
                'name' => 'Sinestesia.co Cap Black',
                'description' => 'Topi baseball hitam dengan logo Sinestesia.co bordir, adjustable strap.',
                'price' => 125000,
                'stock' => 60,
                'status' => 'active',
            ],
            [
                'category_id' => $categoryModels[4]->id,
                'name' => 'Sinestesia.co Tote Bag',
                'description' => 'Tote bag canvas premium dengan logo Sinestesia.co, kapasitas besar dan tahan lama.',
                'price' => 95000,
                'stock' => 45,
                'status' => 'active',
            ],
        ];

        foreach ($products as $prod) {
            $slug = Str::slug($prod['name']);
            Product::create(array_merge($prod, ['slug' => $slug]));
        }

        // Beberapa sample order
        $allCustomers = User::where('role', 'customer')->get();
        $allProducts = Product::all();

        $sampleOrders = [
            ['user' => $allCustomers[0], 'product' => $allProducts[0], 'qty' => 2, 'status' => 'completed'],
            ['user' => $allCustomers[0], 'product' => $allProducts[2], 'qty' => 1, 'status' => 'processed'],
            ['user' => $allCustomers[1], 'product' => $allProducts[4], 'qty' => 1, 'status' => 'pending'],
            ['user' => $allCustomers[2], 'product' => $allProducts[6], 'qty' => 2, 'status' => 'completed'],
            ['user' => $allCustomers[3], 'product' => $allProducts[8], 'qty' => 3, 'status' => 'pending'],
            ['user' => $allCustomers[4], 'product' => $allProducts[1], 'qty' => 1, 'status' => 'processed'],
        ];

        foreach ($sampleOrders as $o) {
            Order::create([
                'user_id' => $o['user']->id,
                'product_id' => $o['product']->id,
                'quantity' => $o['qty'],
                'total_price' => $o['product']->price * $o['qty'],
                'shipping_address' => $o['user']->address,
                'status' => $o['status'],
            ]);
        }
    }
}
