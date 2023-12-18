<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public $admin;
    public $user;

    function __construct()
    {
        $this->admin = Role::where('name', Role::ADMIN)->where('status', 1)->first();
        $this->user = Role::where('name', Role::USER)->where('status', 1)->first();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert($this->data());
    }

    public function data()
    {
        return [
            [
                'id' => Str::uuid(),
                'role_id' => $this->admin->id,
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone' => '9999999999',
                'email' => 'admin@kisan-facility.com',
                'password' => Hash::make("Admin@1234"),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'role_id' => $this->user->id,
                'first_name' => 'Farmer',
                'last_name' => 'User',
                'phone' => '8888888888',
                'email' => 'farmer@kisan-facility.com',
                'password' => Hash::make("Farmer@1234"),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
    }
}
