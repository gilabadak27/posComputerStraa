<?php

namespace App\Filament\Admin\Resources\TransactionItems\Pages;

use App\Filament\Admin\Resources\TransactionItems\TransactionItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionItem extends CreateRecord
{
    protected static string $resource = TransactionItemResource::class;
}
