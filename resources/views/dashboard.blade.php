@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Selamat datang di sistem manajemen laboratorium')

@section('content')
<div class="space-y-6">
    @if(!auth()->user()->role)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-6 py-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-2xl mr-4"></i>
                <div>
                    <h3 class="font-semibold">Akun Belum Divalidasi</h3>
                    <p>Akun Anda telah berhasil dibuat dengan Google. Silakan menunggu admin untuk memberikan role dan akses laboratorium.</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-4"></i>
                <div>
                    <h3 class="font-semibold">Selamat Datang, {{ auth()->user()->nama_pengguna }}!</h3>
                    <p>Anda login sebagai {{ ucfirst(auth()->user()->role) }}. Gunakan menu di sidebar untuk mengakses fitur sistem.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    @if(auth()->user()->role)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-petra-orange to-orange-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-tools text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Perlengkapan</h3>
                    <p class="text-2xl font-bold">{{ \App\Models\Perlengkapan::count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-petra-blue to-blue-800 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-wrench text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Pemeliharaan</h3>
                    <p class="text-2xl font-bold">{{ \App\Models\Pemeliharaan::where('status', 'Dalam Proses')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-handshake text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Peminjaman</h3>
                    <p class="text-2xl font-bold">{{ \App\Models\Peminjaman::where('status', 'Dalam Proses')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-building text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold">Laboratorium</h3>
                    <p class="text-2xl font-bold">{{ auth()->user()->isAdmin() ? \App\Models\Laboratorium::count() : auth()->user()->laboratoriums->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection