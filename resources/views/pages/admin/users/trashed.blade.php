<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-3xl font-serif font-bold text-ink tracking-tight">Data Pengguna Terhapus</h1>
                <p class="text-muted mt-1 font-serif">Restore atau hapus permanen data pengguna yang sudah dihapus.</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="w-full md:w-auto px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center justify-center gap-2">
                <x-lucide-arrow-left class="w-4 h-4" /> Kembali
            </a>
        </div>

        {{-- ALERT: Success Message --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-300 rounded-md p-4 flex items-start gap-3">
                <x-lucide-check-circle class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
                <p class="font-serif text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        {{-- 2. FILTER & PENCARIAN --}}
        <form method="GET" action="{{ route('admin.users.trashed') }}" class="bg-surface border border-ink p-4">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Pencarian</label>
                    <div class="relative">
                        <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-coffee/60" />
                        <input type="text" name="search" placeholder="Cari nama, email, atau ID anggota..."
                            class="w-full pl-9 pr-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-ink rounded-md"
                            value="{{ request('search') }}">
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 md:flex-none px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2">
                        <x-lucide-search class="w-4 h-4" /> Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.users.trashed') }}"
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
                    Menampilkan <span class="font-semibold text-ink">{{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-ink">{{ $users->total() }}</span> pengguna terhapus
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="panel-table w-full text-sm">
                    <thead>
                        <tr class="border-b border-ink bg-ink/5">
                            <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">ID</th>
                            <th class="text-left px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Nama Lengkap</th>
                            <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Email</th>
                            <th class="text-center px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Role</th>
                            <th class="text-center px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Dihapus Tanggal</th>
                            <th class="text-right px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink">
                        @forelse($users as $user)
                            <tr class="hover:bg-red-50/50 transition-colors">
                                <td class="px-6 py-4 font-mono text-coffee font-semibold">{{ $user->formatted_id }}</td>
                                <td class="px-6 py-4 font-serif text-ink">{{ $user->name }}</td>
                                <td class="px-6 py-4 font-mono text-muted text-xs">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 text-xs font-mono border border-ink rounded bg-ink/5 text-coffee">
                                        {{ $user->role_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-muted text-xs">
                                    {{ $user->deleted_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.users.restore', $user->id) }}"
                                            class="inline" onsubmit="return confirm('Restore data pengguna ini?')">
                                            @csrf
                                            <button type="submit"
                                                class="p-1.5 border border-green-500 rounded hover:bg-green-50 transition-colors group"
                                                title="Restore">
                                                <x-lucide-rotate-ccw class="w-4 h-4 text-green-600" />
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            class="inline" onsubmit="return confirm('Hapus permanen data pengguna ini? Tindakan ini tidak dapat dibatalkan!')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 border border-red-500 rounded hover:bg-red-50 transition-colors group"
                                                title="Hapus Permanen">
                                                <x-lucide-trash-2 class="w-4 h-4 text-red-700" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center font-serif text-muted">
                                    <x-lucide-trash-2 class="w-8 h-8 mx-auto mb-3 opacity-40" />
                                    <p>Belum ada pengguna yang dihapus.</p>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="text-coffee hover:text-ink mt-2 inline-block">Kembali ke daftar pengguna →</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <x-pagination :paginator="$users" />

        {{-- INFO PANEL --}}
        <div class="bg-amber-50 border border-amber-300 p-5 rounded-md">
            <div class="flex items-start gap-3">
                <x-lucide-triangle-alert class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" />
                <div class="font-serif text-sm text-amber-900">
                    <p class="font-semibold mb-2">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data yang sudah di-restore akan kembali ke daftar pengguna aktif.</li>
                        <li>Tombol sampah (delete) merah untuk hapus permanen (tidak bisa dipulihkan).</li>
                        <li>Audit trail tetap tersimpan meski pengguna dihapus permanen.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
