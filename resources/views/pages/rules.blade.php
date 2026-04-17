<x-layouts.app>
    <div class="max-w-4xl mx-auto space-y-8 px-2 py-6">

        {{-- Header Page --}}
        <div class="text-center border-b border-ink pb-6">
            <x-lucide-scale class="w-12 h-12 text-ink mx-auto mb-4 stroke-[1.5]" />
            <h1 class="text-3xl font-serif font-bold text-ink tracking-tight">Tata Tertib Perpustakaan</h1>
            <p class="font-serif text-muted mt-2 max-w-2xl mx-auto">
                Peraturan dan panduan penggunaan layanan Scriptoria untuk menjaga kenyamanan, keamanan, dan keberlanjutan koleksi.
            </p>
        </div>

        {{-- Seksi I: Keanggotaan --}}
        <section class="bg-surface border border-ink p-6 md:p-8">
            <h2 class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 mb-4 flex items-center gap-2">
                <x-lucide-user-check class="w-4 h-4 text-coffee" /> I. Keanggotaan
            </h2>

            <div class="space-y-4 font-serif text-ink">
                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">1.1. Pendaftaran</h3>
                    <ul class="space-y-1 list-disc list-inside text-sm">
                        <li>Calon anggota wajib mengisi formulir pendaftaran dan melampirkan identitas valid.</li>
                        <li>Verifikasi keanggotaan dilakukan maksimal <span class="font-mono text-ink">2×24 jam</span> setelah pendaftaran.</li>
                        <li>Kartu anggota digital dapat diunduh melalui halaman profil setelah status <span class="font-mono text-coffee">Aktif</span>.</li>
                    </ul>
                </div>

                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">1.2. Hak Anggota</h3>
                    <ul class="space-y-1 list-disc list-inside text-sm">
                        <li>Meminjam maksimal <span class="font-mono text-ink">4 buku</span> dengan jangka waktu <span class="font-mono text-ink">7 hari</span>.</li>
                        <li>Mengakses katalog digital dan fitur pencarian lanjutan.</li>
                        <li>Mengajukan reservasi untuk buku yang sedang dipinjam.</li>
                        <li>Mendapatkan notifikasi jatuh tempo dan pengingat pengembalian.</li>
                    </ul>
                </div>

                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">1.3. Kewajiban Anggota</h3>
                    <ul class="space-y-1 list-disc list-inside text-sm">
                        <li>Menjaga kondisi fisik buku yang dipinjam; kerusakan akibat kelalaian menjadi tanggung jawab peminjam.</li>
                        <li>Mengembalikan buku tepat waktu; keterlambatan dikenakan denda administrasi.</li>
                        <li>Tidak memperbanyak, mendistribusikan, atau mengkomersialkan konten arsip tanpa izin tertulis.</li>
                        <li>Memperbarui data kontak jika terjadi perubahan.</li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- Seksi II: Peminjaman & Pengembalian --}}
        <section class="bg-surface border border-ink p-6 md:p-8">
            <h2 class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 mb-4 flex items-center gap-2">
                <x-lucide-book-copy class="w-4 h-4 text-coffee" /> II. Peminjaman & Pengembalian
            </h2>

            <div class="space-y-4 font-serif text-ink">
                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">2.1. Alur Peminjaman</h3>
                    <ol class="space-y-1 list-decimal list-inside text-sm">
                        <li>Pilih buku dari katalog dan klik <span class="font-mono text-coffee">Ajukan Peminjaman</span>.</li>
                        <li>Sistem menghasilkan <span class="font-mono text-ink">Kode Booking</span> (contoh: SCR-8F3A92X).</li>
                        <li>Tunjukkan kode booking ke petugas perpustakaan dalam waktu <span class="font-mono text-ink">24 jam</span>.</li>
                        <li>Petugas memverifikasi dan menyerahkan buku fisik/digital.</li>
                        <li>Status transaksi berubah menjadi <span class="font-mono text-coffee">Dipinjam</span>.</li>
                    </ol>
                </div>

                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">2.2. Pengembalian</h3>
                    <ul class="space-y-1 list-disc list-inside text-sm">
                        <li>Buku dapat dikembalikan sebelum atau pada tanggal jatuh tempo.</li>
                        <li>Pengembalian dilakukan di loket perpustakaan atau melalui fitur <span class="font-mono text-coffee">Konfirmasi Pengembalian</span> di dashboard.</li>
                        <li>Petugas akan memeriksa kondisi fisik buku sebelum menutup transaksi.</li>
                        <li>Status berubah menjadi <span class="font-mono text-coffee">Dikembalikan</span> setelah verifikasi selesai.</li>
                    </ul>
                </div>

                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">2.3. Keterlambatan & Denda</h3>
                    <ul class="space-y-1 list-disc list-inside text-sm">
                        <li>Keterlambatan pengembalian dikenakan denda <span class="font-mono text-ink">Rp 1.000/hari/buku</span>.</li>
                        <li>Denda dapat dibayarkan melalui transfer atau tunai di loket perpustakaan.</li>
                        <li>Akun dengan tunggakan denda > <span class="font-mono text-ink">Rp 50.000</span> akan dibekukan sementara hingga pelunasan.</li>
                        <li>Riwayat denda dapat dilihat di halaman <span class="font-mono text-coffee">Profil → Riwayat Transaksi</span>.</li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- Seksi III: Etika & Keamanan --}}
        <section class="bg-surface border border-ink p-6 md:p-8">
            <h2 class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 mb-4 flex items-center gap-2">
                <x-lucide-shield-check class="w-4 h-4 text-coffee" /> III. Etika & Keamanan
            </h2>

            <div class="space-y-4 font-serif text-ink">
                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">3.1. Penggunaan Layanan</h3>
                    <ul class="space-y-1 list-disc list-inside text-sm">
                        <li>Dilarang menggunakan akun orang lain atau membagikan kredensial login.</li>
                        <li>Dilarang melakukan scraping, automated access, atau eksploitasi sistem tanpa izin.</li>
                        <li>Konten arsip hanya untuk keperluan pribadi, edukasi, atau riset non-komersial.</li>
                        <li>Pelanggaran dapat berakibat pada pembekuan akun atau tindakan hukum.</li>
                    </ul>
                </div>

                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">3.2. Privasi Data</h3>
                    <ul class="space-y-1 list-disc list-inside text-sm">
                        <li>Data pribadi anggota (nama, email, kontak) hanya digunakan untuk keperluan administrasi perpustakaan.</li>
                        <li>Riwayat peminjaman tidak dibagikan kepada pihak ketiga tanpa persetujuan tertulis anggota.</li>
                        <li>Anggota berhak meminta penghapusan data sesuai ketentuan yang berlaku.</li>
                    </ul>
                </div>

                <div class="border-l-2 border-ink pl-4">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-coffee mb-2">3.3. Pelanggaran & Sanksi</h3>
                    <div class="overflow-x-auto">
                    <table class="panel-table w-full text-sm mt-2">
                        <thead>
                            <tr class="border-b border-ink">
                                <th class="text-left py-2 font-mono text-xs text-muted">Pelanggaran</th>
                                <th class="text-left py-2 font-mono text-xs text-muted">Sanksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink">
                            <tr>
                                <td class="py-2 font-serif">Keterlambatan pengembalian</td>
                                <td class="py-2 font-mono text-coffee">Denda Rp 1.000/hari</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-serif">Kerusakan buku akibat kelalaian</td>
                                <td class="py-2 font-mono text-coffee">Ganti rugi sesuai nilai buku</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-serif">Penyalahgunaan akun</td>
                                <td class="py-2 font-mono text-coffee">Pembekuan akun 30 hari</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-serif">Pelanggaran hak cipta</td>
                                <td class="py-2 font-mono text-red-700">Pemutusan akun + tindakan hukum</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border border-ink bg-surface p-4">
            <p class="font-serif text-sm text-muted">
                Dokumen ini berlaku sejak <span class="font-mono text-ink">1 Januari 2024</span> dan dapat diperbarui sewaktu-waktu.
            </p>
            <div class="mobile-action-group">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center gap-2">
                    <x-lucide-arrow-left class="w-4 h-4" /> Kembali
                </a>
            </div>
        </div>

        {{-- Info Panel --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-alert-circle class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Pertanyaan?</span> Hubungi tim kurator melalui <a href="#" class="text-coffee hover:text-ink underline">halaman kontak</a> atau kunjungi loket perpustakaan pada hari kerja, 09.00–16.00 WIB.
            </div>
        </div>
    </div>
</x-layouts.app>
