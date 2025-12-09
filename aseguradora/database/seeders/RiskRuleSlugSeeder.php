<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskRuleSlug;

class RiskRuleSlugSeeder extends Seeder
{
    public function run(): void
    {
        $slugs = [
            ['name' => 'Duration Check', 'slug' => 'duration-check'],
            ['name' => 'Volume Consistency', 'slug' => 'volume-consistency'],
            ['name' => 'Time Range Operation', 'slug' => 'time-range-operation'],
            ['name' => 'Max Drawdown', 'slug' => 'max-drawdown'],
            ['name' => 'Daily Loss Limit', 'slug' => 'daily-loss-limit'],
        ];

        foreach ($slugs as $slug) {
            RiskRuleSlug::firstOrCreate(['slug' => $slug['slug']], $slug);
        }
    }
}
