<?php

namespace App\Livewire\Cashier;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionReport extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $datePreset = 'all';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public string $paymentMethod = 'all';

    // Detail Modal State
    public bool $showDetailModal = false;
    public ?Transaction $selectedTransaction = null;

    public function mount(): void
    {
        $this->applyDatePreset('all');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectDatePreset(string $preset): void
    {
        $this->applyDatePreset($preset);
        $this->resetPage();
    }

    public function updatedStartDate(): void
    {
        $this->datePreset = 'custom';
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        $this->datePreset = 'custom';
        $this->resetPage();
    }

    public function updatingPaymentMethod(): void
    {
        $this->resetPage();
    }

    public function applyDatePreset(string $preset): void
    {
        $this->datePreset = $preset;

        switch ($preset) {
            case 'all':
                $this->startDate = null;
                $this->endDate = null;
                break;
            case 'today':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');
                break;
            case 'yesterday':
                $this->startDate = now()->subDay()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->subDay()->endOfDay()->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = now()->startOfWeek()->format('Y-m-d');
                $this->endDate = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'custom':
                if (!$this->startDate) {
                    $this->startDate = now()->startOfMonth()->format('Y-m-d');
                }
                if (!$this->endDate) {
                    $this->endDate = now()->format('Y-m-d');
                }
                break;
        }
    }

    public function showDetails(int $transactionId): void
    {
        $this->selectedTransaction = Transaction::with(['user', 'customer', 'items.product'])->find($transactionId);
        if ($this->selectedTransaction) {
            $this->showDetailModal = true;
        }
    }

    public function printReceipt(int $transactionId): void
    {
        $transaction = Transaction::with(['user', 'customer', 'items.product'])->find($transactionId);
        if (!$transaction) {
            return;
        }

        $items = $transaction->items->map(function ($item) {
            return [
                'name' => $item->product->name ?? 'Produk',
                'quantity' => $item->quantity,
                'price' => (float) $item->price,
                'subtotal' => (float) $item->subtotal,
            ];
        })->toArray();

        $receiptData = [
            'invoice_number' => $transaction->invoice_number,
            'created_at' => $transaction->created_at->format('d/m/Y H:i'),
            'cashier_name' => $transaction->user->name ?? 'Kasir',
            'customer_name' => $transaction->customer->name ?? null,
            'payment_method' => strtoupper($transaction->payment_method),
            'items' => $items,
            'total_amount' => $transaction->total_amount,
            'paid_amount' => $transaction->paid_amount,
            'change_amount' => $transaction->change_amount,
        ];

        $this->dispatch('show-receipt', receiptData: $receiptData);
        $this->showDetailModal = false;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedTransaction = null;
    }

    public function render()
    {
        $query = Transaction::query()
            ->with(['user', 'customer', 'items.product'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('invoice_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($u) {
                            $u->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('customer', function ($c) {
                            $c->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->paymentMethod !== 'all', function ($q) {
                $q->where('payment_method', $this->paymentMethod);
            });

        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->startOfDay();

            if ($startDate->greaterThan($endDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }

            $query
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<', $endDate->copy()->addDay());
        }

        // Clone query for overall statistics
        $statsQuery = clone $query;
        $allTransactions = $statsQuery->get();

        $stats = [
            'total_amount' => $allTransactions->sum('total_amount'),
            'total_count' => $allTransactions->count(),
            'avg_amount' => $allTransactions->count() > 0 ? $allTransactions->avg('total_amount') : 0,
            'cash_count' => $allTransactions->where('payment_method', 'cash')->count(),
            'qris_count' => $allTransactions->where('payment_method', 'qris')->count(),
            'debit_count' => $allTransactions->where('payment_method', 'debit')->count(),
            'credit_count' => $allTransactions->where('payment_method', 'credit')->count(),
        ];

        $transactions = $query->latest()->paginate(10);

        return view('livewire.cashier.transaction-report', [
            'transactions' => $transactions,
            'stats' => $stats,
        ]);
    }
}
