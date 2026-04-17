<x-layouts.app>
    <div class="max-w-6xl mx-auto space-y-6 px-2">
        
        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div class="flex items-start gap-4">
                <a href="{{ route('admin.users.index') }}" class="p-2 border border-ink rounded hover:bg-ink/5 transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5 text-coffee" />
                </a>
                <div>
                    <h1 class="text-2xl font-serif font-bold text-ink tracking-tight">Detail Anggota</h1>
                    <p class="text-muted mt-1 font-serif text-sm">Profil lengkap, riwayat peminjaman, dan status keanggotaan.</p>
                </div>
            </div>
            <div class="mobile-action-group">
                <button class="px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center gap-2">
                    <x-lucide-mail class="w-4 h-4" /> Kirim Notifikasi
                </button>
                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center gap-2">
                    <x-lucide-pencil class="w-4 h-4" /> Edit Profil
                </a>
            </div>
        </div>

        {{-- 2. MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- LEFT: Profile & Quick Stats --}}
            <div class="space-y-6">
                {{-- Profile Card --}}
                <div class="bg-surface border border-ink p-6 flex flex-col items-center text-center">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 bg-background border border-ink rounded-full object-cover mb-4">
                    <h2 class="font-serif text-xl font-bold text-ink">{{ $user->name }}</h2>
                    <p class="font-mono text-sm text-coffee mt-1">ID: {{ $user->formatted_id }} | {{ $user->role_label }}</p>
                    <span class="mt-3 px-3 py-1 border border-ink bg-ink/5 text-xs font-mono uppercase tracking-wider text-ink rounded">
                        {{ $user->status_label }}
                    </span>
                    <div class="mt-6 w-full space-y-3 text-left">
                        <div class="flex flex-col gap-1 border-b border-ink pb-2 sm:flex-row sm:items-center sm:justify-between">
                            <span class="font-mono text-xs text-muted">Email</span>
                            <span class="font-serif text-sm text-ink">{{ $user->email }}</span>
                        </div>
                        <div class="flex flex-col gap-1 border-b border-ink pb-2 sm:flex-row sm:items-center sm:justify-between">
                            <span class="font-mono text-xs text-muted">Telepon</span>
                            <span class="font-serif text-sm text-ink">{{ $user->phone ?? 'Belum ada' }}</span>
                        </div>
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <span class="font-mono text-xs text-muted">Bergabung</span>
                            <span class="font-serif text-sm text-ink">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-4 border-b border-ink pb-2">Informasi Tambahan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Role</span>
                            <span class="font-serif text-ink font-semibold">{{ $user->role_label }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Status</span>
                            <span class="font-serif text-ink font-semibold">{{ $user->status_label }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Email Verified</span>
                            <span class="font-serif text-ink font-semibold">
                                @if($user->isEmailVerified())
                                    <span class="text-green-600">✓ Terverifikasi</span>
                                @else
                                    <span class="text-amber-600">⏳ Menunggu</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Status ID</span>
                            <span class="font-serif text-ink font-semibold">{{ $user->id_number ?? 'Belum ada' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Details & History --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Basic Info --}}
                <div class="bg-surface border border-ink p-6">
                    <h2 class="text-lg font-serif font-semibold text-ink mb-4 border-b border-ink pb-2">Data Lengkap Pengguna</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Nama Lengkap</p>
                            <p class="font-serif text-ink">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Email</p>
                            <p class="font-serif text-ink break-all">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Alamat Domisili</p>
                            <p class="font-serif text-ink">{{ $user->address ?? 'Belum ada' }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Nomor Telepon</p>
                            <p class="font-serif text-ink">{{ $user->phone ?? 'Belum ada' }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Role</p>
                            <p class="font-serif text-ink">{{ $user->role_label }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Status</p>
                            <p class="font-serif text-ink">{{ $user->status_label }}</p>
                        </div>
                    </div>
                </div>

                {{-- Active Loans Table --}}
                <div class="bg-surface border border-ink overflow-hidden">
                    <div class="px-6 py-4 border-b border-ink flex items-center justify-between">
                        <h3 class="font-serif font-semibold text-ink">Peminjaman Aktif</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="panel-table w-full text-sm">
                            <thead>
                                <tr class="border-b border-ink bg-ink/5">
                                    <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">ID</th>
                                    <th class="text-left px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Judul Buku</th>
                                    <th class="text-center px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Pinjam</th>
                                    <th class="text-center px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Jatuh Tempo</th>
                                    <th class="text-center px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-ink">
                                    <td colspan="5" class="px-6 py-8 text-center font-serif text-muted">
                                        <x-lucide-inbox class="w-8 h-8 mx-auto mb-2 opacity-40" />
                                        <p>Belum ada peminjaman aktif</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Internal Notes --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-3 flex items-center gap-2">
                        <x-lucide-file-text class="w-4 h-4 text-coffee" />
                        Catatan Admin
                    </h3>
                    @if($user->admin_notes)
                        <p class="font-serif text-sm text-muted leading-relaxed">
                            {{ $user->admin_notes }}
                        </p>
                    @else
                        <p class="font-serif text-sm text-muted italic">Belum ada catatan admin</p>
                    @endif
                    @if($user->updated_by)
                        <p class="text-xs font-mono text-coffee/60 mt-3">— Diperbarui: {{ $user->updated_at->format('d M Y H:i') }}</p>
                    @else
                        <p class="text-xs font-mono text-coffee/60 mt-3">— Dibuat: {{ $user->created_at->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. INFO PANEL --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-info class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Informasi:</span> Data profil pengguna ditampilkan dari database. Gunakan tombol <span class="text-coffee">Edit Profil</span> untuk memperbarui informasi. Semua perubahan akan tercatat dengan user dan waktu yang melakukan perubahan.
            </div>
        </div>
    </div>
</x-layouts.app>
