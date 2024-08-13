<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\Component;
 
class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([                        
                        $this->getUsernameFormComponent(),
                        $this->getFirstnameFormComponent(),
                        $this->getLastnameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),     
                        $this->getRoleFormComponent()                                                                
                    ])
                    ->statePath('data'),
            ),
        ];
    }
 
    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')            
            ->required();
    }

    protected function getFirstnameFormComponent(): Component
    {
        return TextInput::make('firstname')            
            ->required();
    }
    protected function getLastnameFormComponent(): Component
    {
        return TextInput::make('lastname')            
            ->required();
    }  
    
    protected function getRoleFormComponent(): Component
    {
        return Hidden::make('role')
            ->default('Teknisi');
    }
}