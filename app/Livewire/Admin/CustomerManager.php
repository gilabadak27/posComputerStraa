<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerManager extends Component
{
    use WithPagination;

    // Search and filter state
    public string $search = '';
    public string $statusFilter = '';

    // Form modal state
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $customerId = null;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public int $points = 0;
    public string $status = 'active';

    // Delete Modal state
    public bool $showDeleteModal = false;
    public ?int $customerToDeleteId = null;

    // Alert messages
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'points' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama pelanggan wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'points.integer' => 'Poin harus berupa angka.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $customer = Customer::find($id);
        if (! $customer) {
            $this->errorMessage = 'Pelanggan tidak ditemukan.';
            return;
        }

        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email ?? '';
        $this->phone = $customer->phone ?? '';
        $this->address = $customer->address ?? '';
        $this->points = $customer->points ?? 0;
        $this->status = $customer->status ?? 'active';

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function saveCustomer(): void
    {
        $validatedData = $this->validate();

        try {
            if ($this->isEditing && $this->customerId) {
                $customer = Customer::findOrFail($this->customerId);
                $customer->update($validatedData);
                $this->successMessage = "Data pelanggan '{$customer->name}' berhasil diperbarui.";
            } else {
                $customer = Customer::create($validatedData);
                $this->successMessage = "Pelanggan baru '{$customer->name}' berhasil ditambahkan.";
            }

            $this->closeModal();
        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal menyimpan data pelanggan: ' . $e->getMessage();
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->customerToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCustomer(): void
    {
        if (! $this->customerToDeleteId) {
            return;
        }

        try {
            $customer = Customer::find($this->customerToDeleteId);
            if ($customer) {
                $name = $customer->name;
                $customer->delete();
                $this->successMessage = "Pelanggan '{$name}' berhasil dihapus.";
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal menghapus pelanggan: ' . $e->getMessage();
        }

        $this->showDeleteModal = false;
        $this->customerToDeleteId = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'email', 'phone', 'address', 'points', 'status', 'customerId', 'isEditing']);
        $this->status = 'active';
        $this->resetValidation();
    }

    public function render()
    {
        $customersQuery = Customer::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('address', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->withCount('transactions')
            ->latest();

        $stats = [
            'total' => Customer::count(),
            'active' => Customer::where('status', 'active')->count(),
            'total_points' => Customer::sum('points'),
        ];

        return view('livewire.admin.customer-manager', [
            'customers' => $customersQuery->paginate(10),
            'stats' => $stats,
        ]);
    }
}
