@extends('layouts.app')

@section('style')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        .page-title i {
            color: #149d80;
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background-color: white;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h5 {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0;
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            background-color: #f0fdf4;
            color: #16a34a;
        }

        .user-badge i {
            margin-right: 0.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 10px;
            border-color: #e2e8f0;
            padding: 0.625rem 0.75rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #149d80;
            box-shadow: 0 0 0 0.25rem rgba(0, 114, 79, 0.15);
        }

        .input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
        }

        .password-toggle:hover {
            color: #149d80;
        }

        .helper-text {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.35rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.5;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            border: 1px solid transparent;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            color: #fff;
            background-color: #149d80;
            border-color: #149d80;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #0c8b71;
            border-color: #0c8b71;
            box-shadow: 0 4px 10px rgba(0, 114, 79, 0.2);
            transform: translateY(-2px);
        }

        .btn-secondary {
            color: #fff;
            background-color: #94a3b8;
            border-color: #94a3b8;
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            background-color: #64748b;
            border-color: #64748b;
            box-shadow: 0 4px 10px rgba(100, 116, 139, 0.2);
            transform: translateY(-2px);
        }

        .btn i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .card-footer {
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .breadcrumbs {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item {
            color: #64748b;
        }

        .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: #149d80;
        }

        .breadcrumb-divider {
            margin: 0 0.5rem;
            color: #cbd5e1;
        }

        .text-danger {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #149d80;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f8fafc;
            border-radius: 10px;
        }

        .user-details h5 {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .user-details p {
            color: #64748b;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .card-footer {
                flex-direction: column;
            }

            .card-footer .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="bx bx-user-voice"></i> Edit Pegawai
            </h1>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <!-- Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="breadcrumb-item">
                <a href="{{ route('user.index') }}">Pegawai</a>
            </div>
            <div class="breadcrumb-divider">
                <i class="bx bx-chevron-right"></i>
            </div>
            <div class="breadcrumb-item">Edit Pegawai</div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Data Pegawai</h5>
                <span class="user-badge">
                    <i class="bx bx-user"></i> {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="card-body">
                <!-- User Info -->
                <div class="user-info">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="user-details">
                        <h5>{{ $user->name }}</h5>
                        <p>{{ $user->email }}</p>
                    </div>
                </div>

                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="bx bx-show"></i>
                            </button>
                        </div>
                        <div class="helper-text">Kosongkan jika tidak ingin mengubah password</div>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
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
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            }
        }
    </script>
@endsection
