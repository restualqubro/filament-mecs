<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ProfitChart extends ChartWidget
{
    protected static ?string $heading = 'Profit Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Profit Penjualan',
                    'data' =>   [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],                                
                    'borderColor' => 'rgb(35, 219, 192)',
                ],
                [
                    'label' => 'Profit Service',
                    'data' =>   [0, 10, 6, 2, 22, 32, 55, 44, 65, 45, 77, 89],                                
                    'borderColor' => 'rgb(214, 108, 47)',
                ],                
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    } 
}
