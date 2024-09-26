<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class OmzetChart extends ChartWidget
{
    protected static ?string $heading = 'Omzet';

    // protected int | string | array $columnSpan = 'full';
    

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Omzet Penjualan',
                    'data' =>   [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],                                
                    'borderColor' => 'rgb(75, 192, 192)',
                ],
                [
                    'label' => 'Omzet Service',
                    'data' =>   [0, 10, 6, 2, 22, 32, 55, 44, 65, 45, 77, 89],                                
                    'borderColor' => 'rgb(67, 150, 192)',
                ],                
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // protected function getOmzetSum(): array
    // {
    //     $now = \Carbon\Carbon::now();

    //     $omzetPerMonth = [];

    //     $months = collect(range(1, 12))->map(function($month) use ($now, $omzetPerMonth)){
    //         $count = 
    //     }
    //     return ;
    // }
}
