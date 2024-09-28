<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Get;
use Filament\Actions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;

class DailyReport extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Report';
    
    protected static string $view = 'filament.pages.daily-report';        

    public function mount(): void 
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\DatePicker::make('date')
                        ->label('Select Date')               
                ])
                ->columnSpan(1),                                                                       
            ])            
            ->columns(2)
            ->statePath('data');
    }

    public function getDate(): void
    {
        $this->evaluate($this->date);
    }

    protected function getFormActions(): array
    {         
        $data = getDate();
        return [
            Actions\Action::make('export')
                ->label('Export PDF')
                ->url('/print/dailyreport?'.$data)                
                ->openUrlInNewTab()                            
        ];
    }

    
}
