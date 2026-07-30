<?php

namespace App\Filament\Admin\Resources\TransactionItems\Pages;

use App\Filament\Admin\Resources\TransactionItems\TransactionItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransactionItem extends EditRecord
{
    protected static string $resource = TransactionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
