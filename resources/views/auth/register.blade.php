@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2 mb-4">
                <img src="{{ asset('images/logo.jpg') }}" alt="Sinestesia.co Logo" class="w-10 h-10 object-cover rounded-sm bg-white">
                <span class="font-bold text-xl tracking-widest uppercase">Sinestesia.co</span>
            </div>
            <h1 class="text-2xl font-bold">Buat Akun Baru</h1>
            <p class="text-gray-400 mt-1 text-sm">Bergabung dengan Sinestesia.co</p>
        </div>

        {{-- Form --}}
        <div class="bg-[#111] border border-gray-800 rounded-xl p-8">
            <form method="POST" action="{{ route('register') }}" id="register-form" enctype="multipart/form-data">
                @csrf

                {{-- Profile Photo --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-3">Foto Profil <span class="text-gray-600">(opsional)</span></label>
                    <div class="flex items-center gap-4">
                        <div id="photo-preview-container">
                            <div id="photo-preview-placeholder" class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center border-2 border-dashed border-gray-600 cursor-pointer hover:border-gray-400 transition-colors" onclick="document.getElementById('photo').click()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewRegPhoto(this)">
                            <label for="photo" class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white text-sm px-4 py-2.5 rounded-lg cursor-pointer transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Pilih Foto
                            </label>
                            <p class="text-gray-500 text-xs mt-2">JPG, PNG, atau WebP. Maks 2MB.</p>
                            @error('photo') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            <p id="photo-filename" class="text-green-400 text-xs mt-1 hidden"></p>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="John Doe"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="john@example.com"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-5">
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">No. HP <span class="text-gray-600">(opsional)</span></label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="0812xxxxxxxx"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors"
                    >
                </div>

                {{-- Provinsi --}}
                <div class="mb-5">
                    <label for="province_id" class="block text-sm font-medium text-gray-300 mb-2">Provinsi</label>
                    <select
                        id="province_id"
                        name="province_id"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors @error('province_id') border-red-500 @enderror"
                        required
                    >
                        <option value="" disabled selected>Pilih Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                        @endforeach
                    </select>
                    @error('province_id')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kota/Kabupaten --}}
                <div class="mb-5">
                    <label for="regency_id" class="block text-sm font-medium text-gray-300 mb-2">Kota/Kabupaten</label>
                    <select
                        id="regency_id"
                        name="regency_id"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors disabled:opacity-50 @error('regency_id') border-red-500 @enderror"
                        required
                        disabled
                    >
                        <option value="" disabled selected>Pilih Kota/Kabupaten</option>
                    </select>
                    @error('regency_id')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kecamatan --}}
                <div class="mb-5">
                    <label for="district_id" class="block text-sm font-medium text-gray-300 mb-2">Kecamatan</label>
                    <select
                        id="district_id"
                        name="district_id"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors disabled:opacity-50 @error('district_id') border-red-500 @enderror"
                        required
                        disabled
                    >
                        <option value="" disabled selected>Pilih Kecamatan</option>
                    </select>
                    @error('district_id')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="mb-5">
                    <label for="address" class="block text-sm font-medium text-gray-300 mb-2">Alamat <span class="text-gray-600">(opsional)</span></label>
                    <textarea
                        id="address"
                        name="address"
                        placeholder="Jl. Contoh No. 1, Kota..."
                        rows="2"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors resize-none"
                    >{{ old('address') }}</textarea>
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Min. 6 karakter"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors @error('password') border-red-500 @enderror"
                        required
                    >
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-gray-400 transition-colors"
                        required
                    >
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-white text-black font-semibold py-3 rounded-lg text-sm hover:bg-gray-200 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-white/10">
                    Buat Akun
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-white font-medium hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('province_id');
        const regencySelect = document.getElementById('regency_id');
        const districtSelect = document.getElementById('district_id');

        // Pre-fill values if available (from old input during validation error)
        const oldRegencyId = "{{ old('regency_id') }}";
        const oldDistrictId = "{{ old('district_id') }}";

        function loadRegencies(provinceId, selectedRegencyId = null) {
            regencySelect.innerHTML = '<option value="" disabled selected>Memuat Kota/Kabupaten...</option>';
            regencySelect.disabled = true;
            districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
            districtSelect.disabled = true;

            if (!provinceId) return;

            fetch(`/api/regencies/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    regencySelect.innerHTML = '<option value="" disabled selected>Pilih Kota/Kabupaten</option>';
                    data.forEach(regency => {
                        const option = document.createElement('option');
                        option.value = regency.id;
                        option.textContent = regency.name;
                        if (selectedRegencyId == regency.id) option.selected = true;
                        regencySelect.appendChild(option);
                    });
                    regencySelect.disabled = false;

                    if (selectedRegencyId) {
                        loadDistricts(selectedRegencyId, oldDistrictId);
                    }
                })
                .catch(err => console.error('Error fetching regencies:', err));
        }

        function loadDistricts(regencyId, selectedDistrictId = null) {
            districtSelect.innerHTML = '<option value="" disabled selected>Memuat Kecamatan...</option>';
            districtSelect.disabled = true;

            if (!regencyId) return;

            fetch(`/api/districts/${regencyId}`)
                .then(res => res.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        if (selectedDistrictId == district.id) option.selected = true;
                        districtSelect.appendChild(option);
                    });
                    districtSelect.disabled = false;
                })
                .catch(err => console.error('Error fetching districts:', err));
        }

        provinceSelect.addEventListener('change', function () {
            loadRegencies(this.value);
        });

        regencySelect.addEventListener('change', function () {
            loadDistricts(this.value);
        });

        // Initialize if province is already selected
        if (provinceSelect.value) {
            loadRegencies(provinceSelect.value, oldRegencyId);
        }
    });

    // Photo preview
    window.previewRegPhoto = function(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.getElementById('photo-preview-container');
                container.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-20 h-20 rounded-full object-cover border-2 border-green-500/50 cursor-pointer" onclick="document.getElementById('photo').click()">`;
            };
            reader.readAsDataURL(file);
            const filenameEl = document.getElementById('photo-filename');
            filenameEl.textContent = '✓ ' + file.name;
            filenameEl.classList.remove('hidden');
        }
    };
</script>
@endsection
