<?php

namespace App\Livewire\Cashier;

use Livewire\Attributes\On;
use Livewire\Component;

class ReceiptModal extends Component
{
    public bool $showModal = false;
    public ?array $receiptData = null;

    #[On('show-receipt')]
    public function handleShowReceipt(array $receiptData): void
    {
        $this->receiptData = $receiptData;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->receiptData = null;
    }

    public function render()
    {
        return view('livewire.cashier.receipt-modal');
    }
}
