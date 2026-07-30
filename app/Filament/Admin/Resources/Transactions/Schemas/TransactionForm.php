<?php

namespace App\Filament\Admin\Resources\Transactions\Schemas;

use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('invoice_number')
                    ->label('Invoice Number')
                    ->disabled()
                    ->default(fn (): string => Transaction::generateInvoiceNumber()),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('paid_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('change_amount')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'debit' => 'Debit',
                        'credit' => 'Credit',
                    ])
                    ->default('cash')
                    ->required(),
            ]);
    }
}
