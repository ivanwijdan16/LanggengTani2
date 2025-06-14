@extends('layouts.app')

@section('style')
    @vite(['resources/css/profile/edit.css'])
@endsection

@section('content')
    <div class="container py-4">
        <div class="profile-card">
            <div class="profile-header">
                <h1 class="profile-title">
                    <i class="bx bx-user-circle"></i> Edit Profil
                </h1>
            </div>

            <!-- Show success message if any -->
            @if (session('success'))
                <div class="alert alert-success mx-4 mt-4">
                    <i class="bx bx-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Form to update the profile -->
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="profile-body">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h2 class="form-section-title">
                            <i class="bx bx-user"></i> Informasi Pribadi
                        </h2>

                        <!-- Edit Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            @error('name')
                                <div class="text-danger">
                                    <i class="bx bx-error-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Edit Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                            @error('email')
                                <div class="text-danger">
                                    <i class="bx bx-error-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="form-section">
                        <h2 class="form-section-title">
                            <i class="bx bx-lock-alt"></i> Ubah Password
                        </h2>

                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password"
                                    required>
                                <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger">
                                    <i class="bx bx-error-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password">
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger">
                                    <i class="bx bx-error-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                                <button type="button" class="password-toggle"
                                    onclick="togglePassword('password_confirmation')">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="profile-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.currentTarget.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
@endsection
