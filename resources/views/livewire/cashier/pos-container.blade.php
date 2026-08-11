<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    <!-- LEFT: Product Catalog & Category Filters -->
    <div class="lg:col-span-7 xl:col-span-8">
        @livewire('cashier.product-catalog')
    </div>

    <!-- RIGHT: Cart Summary & Checkout -->
    <div class="lg:col-span-5 xl:col-span-4 sticky top-6">
        @livewire('cashier.cart-section')
    </div>

    <!-- RECEIPT MODAL OVERLAY -->
    @livewire('cashier.receipt-modal')
</div>
