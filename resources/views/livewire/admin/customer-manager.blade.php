<div class="space-y-6">
    <!-- Success & Error Alerts -->
    @if ($successMessage)
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">✓</div>
                <span class="text-sm font-semibold">{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', null)" class="text-emerald-500 hover:text-emerald-700 font-bold text-sm">✕</button>
        </div>
    @endif

    @if ($errorMessage)
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold">!</div>
                <span class="text-sm font-semibold">{{ $errorMessage }}</span>
            </div>
            <button wire:click="$set('errorMessage', null)" class="text-rose-500 hover:text-rose-700 font-bold text-sm">✕</button>
        </div>
    @endif

    <!-- Header Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xl">
                👥
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pelanggan</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl">
                ⚡
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pelanggan Aktif</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($stats['active']) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-black text-xl">
                ⭐
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Poin Member</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($stats['total_points']) }} pts</h3>
            </div>
        </div>
    </div>

    <!-- Main Content Box -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Controls: Search, Filter, Add Button -->
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex flex-1 flex-col sm:flex-row gap-3">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        🔍
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama, email, no HP, atau alamat..."
                        class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                    />
                </div>

                <!-- Status Filter -->
                <select 
                    wire:model.live="statusFilter"
                    class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Non-Aktif</option>
                </select>
            </div>

            <!-- Add Customer Button -->
            <button 
                wire:click="openCreateModal"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2">
                <span>+</span>
                <span>Tambah Pelanggan</span>
            </button>
        </div>

        <!-- Customer Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/60 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-5">Pelanggan</th>
                        <th class="py-3.5 px-5">Kontak</th>
                        <th class="py-3.5 px-5">Alamat</th>
                        <th class="py-3.5 px-5 text-center">Poin</th>
                        <th class="py-3.5 px-5 text-center">Transaksi</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Customer Name & Avatar -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-sm uppercase">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $customer->name }}</p>
                                        <p class="text-xs text-slate-400">ID: #CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="py-4 px-5">
                                <div class="text-xs space-y-0.5">
                                    <p class="font-semibold text-slate-700">{{ $customer->phone ?: '-' }}</p>
                                    <p class="text-slate-400">{{ $customer->email ?: 'Tanpa Email' }}</p>
                                </div>
                            </td>

                            <!-- Address -->
                            <td class="py-4 px-5">
                                <p class="text-xs text-slate-600 max-w-xs truncate">{{ $customer->address ?: '-' }}</p>
                            </td>

                            <!-- Points -->
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-black rounded-lg">
                                    ⭐ {{ number_format($customer->points) }}
                                </span>
                            </td>

                            <!-- Transactions Count -->
                            <td class="py-4 px-5 text-center font-bold text-slate-700">
                                {{ $customer->transactions_count }}x
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-5 text-center">
                                @if ($customer->status === 'active')
                                    <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-lg">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-500 text-xs font-bold rounded-lg">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right space-x-2">
                                <button 
                                    wire:click="openEditModal({{ $customer->id }})"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-bold text-xs rounded-lg transition-colors">
                                    Edit
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $customer->id }})"
                                    class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs rounded-lg transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 font-medium">
                                <p class="text-3xl mb-2">👤</p>
                                <p class="text-sm">Belum ada data pelanggan yang sesuai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- CREATE / EDIT MODAL -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4 animate-in fade-in zoom-in duration-150">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $isEditing ? 'Edit Data Pelanggan' : 'Tambah Pelanggan Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="saveCustomer" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nama Lengkap *</label>
                        <input 
                            type="text" 
                            wire:model="name"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                        />
                        @error('name') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email & Phone Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nomor Telepon / HP</label>
                            <input 
                                type="text" 
                                wire:model="phone"
                                placeholder="08123456789"
                                class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                            />
                            @error('phone') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Email</label>
                            <input 
                                type="email" 
                                wire:model="email"
                                placeholder="budi@example.com"
                                class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                            />
                            @error('email') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Alamat</label>
                        <textarea 
                            wire:model="address"
                            rows="2"
                            placeholder="Jl. Merdeka No. 12, Jakarta"
                            class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                        ></textarea>
                        @error('address') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Points & Status Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Poin Member</label>
                            <input 
                                type="number" 
                                wire:model="points"
                                min="0"
                                class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                            />
                            @error('points') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Status</label>
                            <select 
                                wire:model="status"
                                class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                <option value="active">Aktif</option>
                                <option value="inactive">Non-Aktif</option>
                            </select>
                            @error('status') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                        <button 
                            type="button"
                            wire:click="closeModal" 
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-colors shadow-xs">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- DELETE CONFIRMATION MODAL -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-sm w-full p-6 text-center space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 mx-auto flex items-center justify-center font-bold text-xl">
                    🗑️
                </div>
                <div>
                    <h4 class="text-base font-bold text-slate-800">Hapus Pelanggan?</h4>
                    <p class="text-xs text-slate-500 mt-1">Data pelanggan yang telah dihapus tidak dapat dikembalikan lagi.</p>
                </div>

                <div class="flex gap-2 pt-2">
                    <button 
                        wire:click="$set('showDeleteModal', false)" 
                        class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                        Batal
                    </button>
                    <button 
                        wire:click="deleteCustomer" 
                        class="flex-1 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition-colors shadow-xs">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
