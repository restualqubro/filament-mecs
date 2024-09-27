<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ServiceDataWidget extends ChartWidget
{
    protected static ?string $heading = 'Service Data Chart';

    protected function getData(): array
    {
        return [
            'datasets' => [                
                [
                    'label' => 'Pending',
                    'data' =>   [0, 10, 6, 2, 22, 32, 55, 44, 65, 45, 77, 89],                                
                    'borderColor' => 'rgb(67, 150, 192)',
                ],                
                [
                    'label' => 'Selesai',
                    'data' =>   [0, 10, 6, 2, 22, 32, 55, 44, 65, 45, 77, 89],                                
                    'borderColor' => 'rgb(32, 199, 168)',
                ],                
                [
                    'label' => 'Cancel',
                    'data' =>   [0, 10, 6, 2, 22, 32, 55, 44, 65, 45, 77, 89],                                
                    'borderColor' => 'rgb(255, 99, 132)',
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
