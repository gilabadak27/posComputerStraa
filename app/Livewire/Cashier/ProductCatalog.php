<?php

namespace App\Livewire\Cashier;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductCatalog extends Component
{
    public string $search = '';
    public ?int $selectedCategory = null;

    public function selectCategory(?int $categoryId = null): void
    {
        $this->selectedCategory = $categoryId;
    }

    public function addToCart(int $productId): void
    {
        $this->dispatch('add-to-cart', productId: $productId);
    }

    public function render()
    {
        $categories = Category::withCount('products')->get();

        $products = Product::active()
            ->with('category')
            ->when($this->selectedCategory, fn ($query) => $query->where('category_id', $this->selectedCategory))
            ->when($this->search, fn ($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->get();

        return view('livewire.cashier.product-catalog', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
