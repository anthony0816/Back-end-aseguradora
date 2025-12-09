<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskAction;

class RiskActionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = [
            ['name' => 'Notificar Email', 'slug' => 'notify-email'],
            ['name' => 'Deshabilitar Cuenta', 'slug' => 'disable-account'],
            ['name' => 'Deshabilitar Trading', 'slug' => 'disable-trading'],
            ['name' => 'Cerrar Trades Abiertos', 'slug' => 'close-open-trades'],
            ['name' => 'Notificar Admin', 'slug' => 'notify-admin'],
            ['name' => 'Registrar Log', 'slug' => 'log-incident'],
        ];

        foreach ($actions as $action) {
            RiskAction::firstOrCreate(['slug' => $action['slug']], $action);
        }
    }
}
