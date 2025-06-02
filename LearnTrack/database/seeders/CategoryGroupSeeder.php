<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryGroup;

class CategoryGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            ['name' => '基礎教科'],
            ['name' => '専門学'],
            ['name' => '試験対策'],
            ['name' => '自己管理'],
            ['name' => 'その他'],
        ];

        foreach ($groups as $group) {
            CategoryGroup::create($group);
        }
    }
}
