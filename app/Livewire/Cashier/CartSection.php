<?php

namespace App\Livewire\Cashier;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class CartSection extends Component
{
    // Cart state: [ product_id => ['id', 'name', 'price', 'quantity', 'stock', 'unit', 'subtotal'] ]
    public array $cart = [];

    // Customer & Payment state
    public ?int $customerId = null;
    public string $paymentMethod = 'cash';
    public $paidAmount = '';

    // Alert Messages
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    #[On('add-to-cart')]
    public function handleAddToCart(int $productId): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $product = Product::active()->find($productId);

        if (! $product) {
            $this->errorMessage = 'Produk tidak ditemukan atau tidak aktif.';
            return;
        }

        if ($product->stock <= 0) {
            $this->errorMessage = "Stok untuk produk '{$product->name}' telah habis!";
            return;
        }

        $currentQty = isset($this->cart[$productId]) ? $this->cart[$productId]['quantity'] : 0;

        if ($currentQty + 1 > $product->stock) {
            $this->errorMessage = "Stok tidak mencukupi! Stok tersisa: {$product->stock}";
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['quantity'] * $this->cart[$productId]['price'];
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->sale_price,
                'quantity' => 1,
                'stock' => $product->stock,
                'unit' => $product->unit ?? 'pcs',
                'subtotal' => (float) $product->sale_price,
            ];
        }
    }

    public function updateQuantity(int $productId, int $delta): void
    {
        $this->errorMessage = null;

        if (! isset($this->cart[$productId])) {
            return;
        }

        $newQty = $this->cart[$productId]['quantity'] + $delta;

        if ($newQty <= 0) {
            $this->removeFromCart($productId);
            return;
        }

        $product = Product::find($productId);

        if ($product && $newQty > $product->stock) {
            $this->errorMessage = "Stok tidak mencukupi! Maksimal: {$product->stock}";
            return;
        }

        $this->cart[$productId]['quantity'] = $newQty;
        $this->cart[$productId]['subtotal'] = $newQty * $this->cart[$productId]['price'];
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->customerId = null;
        $this->paidAmount = '';
        $this->errorMessage = null;
    }

    public function getTotalProperty(): float
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function getChangeProperty(): float
    {
        $paid = (float) str_replace(['.', ','], '', (string) $this->paidAmount);
        $total = $this->total;

        return max(0, $paid - $total);
    }

    public function checkout(): void
    {
        $this->errorMessage = null;

        if (empty($this->cart)) {
            $this->errorMessage = 'Keranjang belanja masih kosong!';
            return;
        }

        $total = $this->total;
        $paid = (float) str_replace(['.', ','], '', (string) $this->paidAmount);

        if ($this->paymentMethod === 'cash' && $paid < $total) {
            $this->errorMessage = 'Jumlah pembayaran tunai kurang dari total transaksi!';
            return;
        }

        if ($this->paymentMethod !== 'cash') {
            $paid = $total;
        }

        try {
            DB::transaction(function () use ($total, $paid, &$transaction) {
                // Check stock lock before creating
                foreach ($this->cart as $productId => $item) {
                    $prod = Product::lockForUpdate()->find($productId);
                    if (! $prod || $prod->stock < $item['quantity']) {
                        throw new \Exception("Stok produk '{$item['name']}' tidak mencukupi lagi.");
                    }
                }

                $transaction = Transaction::create([
                    'user_id' => Auth::id() ?? 1,
                    'customer_id' => $this->customerId ?: null,
                    'invoice_number' => Transaction::generateInvoiceNumber(),
                    'total_amount' => $total,
                    'paid_amount' => $paid,
                    'change_amount' => max(0, $paid - $total),
                    'payment_method' => $this->paymentMethod,
                ]);

                foreach ($this->cart as $productId => $item) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    Product::where('id', $productId)->decrement('stock', $item['quantity']);
                }

                if ($this->customerId) {
                    $earnedPoints = (int) floor($total / 10000);
                    if ($earnedPoints > 0) {
                        Customer::where('id', $this->customerId)->increment('points', $earnedPoints);
                    }
                }
            });

            $customer = $this->customerId ? Customer::find($this->customerId) : null;

            $receiptData = [
                'invoice_number' => $transaction->invoice_number,
                'created_at' => $transaction->created_at->format('d/m/Y H:i'),
                'cashier_name' => Auth::user()->name ?? 'Kasir',
                'customer_name' => $customer ? $customer->name : null,
                'payment_method' => strtoupper($transaction->payment_method),
                'items' => array_values($this->cart),
                'total_amount' => $transaction->total_amount,
                'paid_amount' => $transaction->paid_amount,
                'change_amount' => $transaction->change_amount,
            ];

            // Dispatch receipt event to ReceiptModal component
            $this->dispatch('show-receipt', receiptData: $receiptData);

            $this->clearCart();
            $this->successMessage = 'Transaksi berhasil diproses!';

        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal memproses transaksi: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.cashier.cart-section', [
            'customers' => Customer::active()->orderBy('name')->get(),
        ]);
    }
}
