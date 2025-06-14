@extends('layouts.app')

@section('style')
    <style>
        .error-container {
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .error-content {
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .error-code {
            font-size: 120px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(45deg, #149d80, #0c8b71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.125rem;
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-icon {
            font-size: 4rem;
            color: #149d80;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background-color: #149d80;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .btn-back:hover {
            background-color: #0c8b71;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 114, 79, 0.2);
            color: white;
        }

        .btn-back i {
            margin-right: 0.5rem;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background-color: #e2e8f0;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #cbd5e1;
            color: #334155;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-login i {
            margin-right: 0.5rem;
        }

        .access-info {
            background-color: #f8fafc;
            border-left: 4px solid #ef4444;
            padding: 1rem 1.5rem;
            margin-top: 2rem;
            border-radius: 0 8px 8px 0;
        }

        .access-info h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #ef4444;
            margin-bottom: 0.5rem;
        }

        .access-info p {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 80px;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-message {
                font-size: 1rem;
            }

            .error-icon {
                font-size: 3rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="error-container">
        <div class="error-content">
            <i class="bx bx-shield-x error-icon"></i>
            <div class="error-code">403</div>
            <h1 class="error-title">Akses Ditolak</h1>
            <p class="error-message">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
                Halaman ini hanya dapat diakses oleh pengguna dengan role tertentu.
            </p>

            <div class="mb-4">
                <a href="{{ route('home') }}" class="btn-back">
                    <i class="bx bx-home-alt"></i> Kembali ke Beranda
                </a>
                @if (!auth()->check())
                    <a href="{{ route('login') }}" class="btn-login">
                        <i class="bx bx-log-in"></i> Login
                    </a>
                @endif
            </div>

            <div class="access-info">
                <h4>Informasi Akses</h4>
                <p>
                    Halaman ini membutuhkan role {{ request()->route('role') ?? 'khusus' }} untuk dapat diakses.
                    @if (auth()->check())
                        Anda saat ini login sebagai: {{ auth()->user()->role }}
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection
