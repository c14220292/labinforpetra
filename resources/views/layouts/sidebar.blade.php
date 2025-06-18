<div class="bg-petra-blue text-white w-64 flex-shrink-0">
    <div class="p-4">
        <div class="flex items-center space-x-3 mb-8">
            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/4/4d/UK_PETRA_LOGO.svg/1200px-UK_PETRA_LOGO.svg.png" 
                 alt="Petra Logo" class="h-8">
            <div>
                <h2 class="text-lg font-bold">Petra Lab</h2>
                <p class="text-xs text-gray-300">Management System</p>
            </div>
        </div>

        <nav class="space-y-2">
            @auth
                @if(auth()->user()->isAdmin())
                    <!-- Admin Menu -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('admin.dashboard') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.pengguna.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('admin.pengguna.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                    <a href="{{ route('admin.laboratorium.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('admin.laboratorium.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Manajemen Lab</span>
                    </a>
                    <a href="{{ route('admin.perlengkapan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('admin.perlengkapan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-tools"></i>
                        <span>Data Perlengkapan</span>
                    </a>
                    <a href="{{ route('admin.pemeliharaan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('admin.pemeliharaan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-wrench"></i>
                        <span>Pemeliharaan</span>
                    </a>
                    <a href="{{ route('admin.peminjaman.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('admin.peminjaman.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-handshake"></i>
                        <span>Peminjaman</span>
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('admin.laporan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('admin.activity-logs.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Log Aktivitas</span>
                    </a>
                @elseif(auth()->user()->isKepalaLab())
                    <!-- Kepala Lab Menu -->
                    <a href="{{ route('kepalalab.dashboard') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('kepalalab.dashboard') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('kepalalab.perlengkapan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('kepalalab.perlengkapan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-tools"></i>
                        <span>Data Perlengkapan</span>
                    </a>
                    <a href="{{ route('kepalalab.pemeliharaan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('kepalalab.pemeliharaan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-wrench"></i>
                        <span>Pemeliharaan</span>
                    </a>
                    <a href="{{ route('kepalalab.peminjaman.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('kepalalab.peminjaman.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-handshake"></i>
                        <span>Peminjaman</span>
                    </a>
                    <a href="{{ route('kepalalab.laporan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('kepalalab.laporan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                    <a href="{{ route('kepalalab.asisten.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('kepalalab.asisten.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-user-friends"></i>
                        <span>Kelola Asisten</span>
                    </a>
                    <a href="{{ route('kepalalab.activity-logs.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('kepalalab.activity-logs.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Log Aktivitas</span>
                    </a>
                @elseif(auth()->user()->isAsistenLab())
                    <!-- Asisten Lab Menu -->
                    <a href="{{ route('asistenlab.dashboard') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('asistenlab.dashboard') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('asistenlab.perlengkapan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('asistenlab.perlengkapan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-tools"></i>
                        <span>Data Perlengkapan</span>
                    </a>
                    <a href="{{ route('asistenlab.pemeliharaan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('asistenlab.pemeliharaan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-wrench"></i>
                        <span>Pemeliharaan</span>
                    </a>
                    <a href="{{ route('asistenlab.peminjaman.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('asistenlab.peminjaman.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-handshake"></i>
                        <span>Peminjaman</span>
                    </a>
                    <a href="{{ route('asistenlab.laporan.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('asistenlab.laporan.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                    <a href="{{ route('asistenlab.activity-logs.index') }}" 
                       class="flex items-center space-x-3 p-3 rounded-lg hover:bg-petra-orange transition {{ request()->routeIs('asistenlab.activity-logs.*') ? 'bg-petra-orange' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Log Aktivitas</span>
                    </a>
                @else
                    <!-- No Role Menu -->
                    <div class="p-3 text-center text-gray-300">
                        <i class="fas fa-clock text-2xl mb-2"></i>
                        <p class="text-sm">Menunggu role dari admin</p>
                    </div>
                @endif
            @endauth
        </nav>
    </div>
</div>