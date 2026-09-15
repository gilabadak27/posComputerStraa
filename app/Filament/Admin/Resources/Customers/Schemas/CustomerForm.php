<?php

namespace App\Filament\Admin\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Pelanggan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(30),
                TextInput::make('points')
                    ->label('Poin Member')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Non-Aktif',
                    ])
                    ->default('active')
                    ->required(),
                Textarea::make('address')
                    ->label('Alamat')
                    ->columnSpanFull(),
            ]);
    }
}
