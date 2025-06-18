<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex justify-between items-center px-6 py-4">
        <div>
            <h1 class="text-2xl font-bold text-petra-blue">@yield('page-title', 'Dashboard')</h1>
            <p class="text-gray-600 text-sm">@yield('page-description', 'Selamat datang di sistem manajemen laboratorium')</p>
        </div>

        @auth
        <div class="relative">
            <button onclick="toggleUserPopup()" 
                    class="flex items-center space-x-3 bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-200 transition">
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full">
                @else
                    <i class="fas fa-user-circle text-gray-600 text-xl"></i>
                @endif
                <div class="text-left">
                    <div class="text-sm font-medium">{{ auth()->user()->nama_pengguna }}</div>
                    <div class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role ?? 'Belum ada role') }}</div>
                </div>
                <i class="fas fa-chevron-down text-gray-400"></i>
            </button>
            
            <div id="userPopup" 
                 class="hidden absolute right-0 top-12 bg-white border border-gray-300 rounded-lg shadow-lg w-80 z-50">
                <div class="p-4 border-b">
                    <div class="flex items-center space-x-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-12 h-12 rounded-full">
                        @else
                            <i class="fas fa-user-circle text-gray-600 text-3xl"></i>
                        @endif
                        <div>
                            <p class="font-medium">{{ auth()->user()->nama_pengguna }}</p>
                            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                            <p class="text-xs text-petra-blue">{{ ucfirst(auth()->user()->role ?? 'Belum ada role') }}</p>
                        </div>
                    </div>
                </div>
                
                @if(auth()->user()->role && auth()->user()->laboratoriums->count() > 0)
                <div class="p-4 border-b">
                    <p class="text-sm font-medium text-gray-700 mb-2">Akses Laboratorium:</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach(auth()->user()->laboratoriums as $lab)
                            <span class="inline-block bg-petra-blue text-white text-xs px-2 py-1 rounded">
                                {{ $lab->kode_lab }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="p-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin logout?')"
                                class="w-full bg-petra-orange text-white px-4 py-2 rounded hover:bg-orange-600 transition flex items-center justify-center space-x-2">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </div>
</header>

@push('scripts')
<script>
function toggleUserPopup() {
    const popup = document.getElementById('userPopup');
    popup.classList.toggle('hidden');
}

// Close popup when clicking outside
document.addEventListener('click', function(event) {
    const popup = document.getElementById('userPopup');
    const button = event.target.closest('button');
    
    if (!button || button.getAttribute('onclick') !== 'toggleUserPopup()') {
        popup.classList.add('hidden');
    }
});
</script>
@endpush