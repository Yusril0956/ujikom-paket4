<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-3xl font-serif font-bold text-ink tracking-tight">Data Pengguna Perpustakaan</h1>
                <p class="text-muted mt-1 font-serif">Manajemen anggota, status keanggotaan, dan riwayat akun.</p>
            </div>
            <div class="mobile-action-group">
                <a href="{{ route('admin.users.trashed') }}"
                    class="px-4 py-2.5 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center gap-2">
                    <x-lucide-trash-2 class="w-4 h-4" /> Data Terhapus
                </a>
                <a href="{{ route('admin.users.create') }}"
                    class="w-full md:w-auto px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md shadow-[var(--elevation-1)] flex items-center justify-center gap-2">
                    <x-lucide-user-plus class="w-4 h-4" /> Tambah Anggota
                </a>
            </div>
        </div>

        {{-- ALERT: Success Message --}}
        @if (session('success'))
        <div class="bg-green-50 border border-green-300 rounded-md p-4 flex items-start gap-3">
            <x-lucide-check-circle class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
            <p class="font-serif text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        {{-- 2. FILTER & PENCARIAN --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="bg-surface border border-ink p-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1fr)_auto_auto_auto] md:items-end">
                <div class="flex-1">
                    <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Pencarian</label>
                    <div class="relative">
                        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-coffee/60" />
                        <input type="text" name="search" placeholder="Cari nama, email, atau ID anggota..."
                            class="w-full pl-9 pr-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-ink rounded-md"
                            value="{{ request('search') }}">
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-coffee focus:outline-none focus:ring-1 focus:ring-ink rounded-md md:w-auto">
                        <option value="">Semua Status</option>
                        <option value="aktif" @selected(request('status')==='aktif' )>Aktif</option>
                        <option value="pending" @selected(request('status')==='pending' )>Pending</option>
                        <option value="nonaktif" @selected(request('status')==='nonaktif' )>Nonaktif</option>
                    </select>
                </div>

                <div class="w-full md:w-auto">
                    <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Role</label>
                    <select name="role"
                        class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-coffee focus:outline-none focus:ring-1 focus:ring-ink rounded-md md:w-auto">
                        <option value="">Semua Role</option>
                        <option value="admin" @selected(request('role')==='admin' )>Admin</option>
                        <option value="petugas" @selected(request('role')==='petugas' )>Petugas</option>
                        <option value="anggota" @selected(request('role')==='anggota' )>Anggota</option>
                    </select>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 md:flex-none px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2">
                        <x-lucide-search class="w-4 h-4" /> Cari
                    </button>
                    @if (request('search') || request('status') || request('role'))
                    <a href="{{ route('admin.users.index') }}"
                        class="flex-1 md:flex-none px-4 py-2.5 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md text-center">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- 3. TABEL DATA --}}
        <div class="bg-surface border border-ink">
            <div class="px-6 py-4 border-b border-ink flex items-center justify-between">
                <span class="text-xs font-mono text-muted">
                    Menampilkan <span
                        class="font-semibold text-ink">{{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }}</span>
                    dari <span class="font-semibold text-ink">{{ $users->total() }}</span> anggota
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="panel-table w-full text-sm">
                    <thead>
                        <tr class="border-b border-ink bg-ink/5">
                            <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">ID
                            </th>
                            <th class="text-left px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Nama
                                Lengkap</th>
                            <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Email
                                / Kontak</th>
                            <th class="text-center px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">
                                Role</th>
                            <th class="text-center px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">
                                Status</th>
                            <th class="text-center px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">
                                Bergabung</th>
                            <th class="text-right px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink">
                        @forelse($users as $user)
                        <tr class="hover:bg-ink/5 transition-colors">
                            <td class="px-6 py-4 font-mono text-coffee font-semibold">{{ $user->formatted_id }}</td>
                            <td class="px-6 py-4 font-serif text-ink">
                                <div class="flex items-center gap-2">
                                    @if ($user->avatar && Storage::disk('public')->exists($user->avatar))
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                        {{ $user->name }}
                                    @else
                                        <x-lucide-circle-user-round class="w-10 h-10 text-muted" />
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-muted text-xs">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $user->email }}</span>
                                    @if ($user->phone)
                                    <span class="text-coffee">{{ $user->phone }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-2 py-0.5 text-xs font-mono border border-ink rounded bg-ink/5 text-coffee">
                                    {{ $user->role_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                $badge = match ($user->status) {
                                'aktif' => 'text-ink bg-green-100 border-green-300',
                                'pending' => 'text-coffee bg-amber-100 border-amber-300',
                                'nonaktif' => 'text-muted bg-gray-100 border-gray-300',
                                };
                                @endphp
                                <span
                                    class="px-2 py-0.5 text-xs font-mono border rounded uppercase tracking-wider {{ $badge }}">
                                    {{ $user->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-muted text-xs">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="p-1.5 border border-ink rounded hover:bg-ink/5 transition-colors group"
                                        title="Lihat Detail">
                                        <x-lucide-eye class="w-4 h-4 text-coffee/70 group-hover:text-ink" />
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="p-1.5 border border-ink rounded hover:bg-ink/5 transition-colors group"
                                        title="Edit">
                                        <x-lucide-pencil class="w-4 h-4 text-coffee/70 group-hover:text-ink" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        class="inline"
                                        onsubmit="return confirm('Hapus data pengguna ini? Data akan dipindahkan ke trash.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 border border-ink rounded hover:bg-red-50 hover:border-red-300 transition-colors group"
                                            title="Hapus (Soft Delete)">
                                            <x-lucide-trash-2
                                                class="w-4 h-4 text-coffee/70 group-hover:text-red-700" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center font-serif text-muted">
                                <x-lucide-users class="w-8 h-8 mx-auto mb-3 opacity-40" />
                                <p>Belum ada data pengguna.</p>
                                <a href="{{ route('admin.users.create') }}"
                                    class="text-coffee hover:text-ink mt-2 inline-block">Tambah anggota pertama
                                    →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <x-pagination :paginator="$users" />

        {{-- 5. CATATAN SISTEM --}}
        <div class="bg-blue-50 border border-blue-300 p-5 rounded-md">
            <div class="flex items-start gap-3">
                <x-lucide-info class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" />
                <div class="font-serif text-sm text-blue-900">
                    <p class="font-semibold mb-2">Informasi Sistem:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Soft delete: Data yang dihapus dapat dipulihkan melalui menu <strong>Data Terhapus</strong>.
                        </li>
                        <li>Avatar: Jika belum diunggah, sistem akan membuat avatar otomatis dari nama pengguna.</li>
                        <li>Tracking: Setiap perubahan data dicatat dengan ID user dan timestamp untuk audit trail.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
