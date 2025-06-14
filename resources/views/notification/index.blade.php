@extends('layouts.app')

@section('style')
    <style>
        .notification-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .page-title i {
            color: #149d80;
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .notification-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
            border: none;
        }

        .notification-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .notification-item {
            padding: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: flex-start;
            transition: background-color 0.2s;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f8fafc;
        }

        .notification-icon {
            margin-right: 1rem;
            width: 40px;
            height: 40px;
            min-width: 40px;
            background-color: rgba(0, 114, 79, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #149d80;
            font-size: 1.25rem;
        }

        .notification-content {
            flex: 1;
        }

        .notification-message {
            color: #1e293b;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .notification-time {
            color: #94a3b8;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
        }

        .notification-time i {
            margin-right: 0.4rem;
            font-size: 0.9rem;
        }

        .notification-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #64748b;
        }

        .notification-empty-icon {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .notification-empty-text {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .notification-empty-subtext {
            font-size: 0.9rem;
            max-width: 400px;
            margin: 0 auto;
        }

        .notification-date-group {
            padding: 0.75rem 1.25rem;
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .notification-date-group i {
            margin-right: 0.5rem;
        }

        /* Badge styles */
        .notification-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-left: 0.5rem;
        }

        .badge-info {
            background-color: #e0f2fe;
            color: #0284c7;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #d97706;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #dc2626;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .notification-item {
                padding: 1rem;
            }

            .notification-icon {
                width: 36px;
                height: 36px;
                min-width: 36px;
                font-size: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="notification-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="bx bx-bell"></i> Notifikasi
            </h1>
        </div>

        <!-- Notifications Card -->
        <div class="notification-card">
            @if (count($notifications) > 0)
                <ul class="notification-list">
                    @php
                        $currentDate = null;
                    @endphp

                    @foreach ($notifications as $notification)
                        @php
                            $notificationDate = \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d');
                            $isToday = \Carbon\Carbon::parse($notification->created_at)->isToday();
                            $isYesterday = \Carbon\Carbon::parse($notification->created_at)->isYesterday();
                            $formattedDate = $isToday
                                ? 'Hari Ini'
                                : ($isYesterday
                                    ? 'Kemarin'
                                    : \Carbon\Carbon::parse($notification->created_at)->format('d F Y'));

                            // Determine notification type based on message content (example logic)
                            $type = 'info';
                            if (str_contains(strtolower($notification->message), 'kadaluarsa')) {
                                $type = 'danger';
                            } elseif (str_contains(strtolower($notification->message), 'hampir')) {
                                $type = 'warning';
                            } elseif (str_contains(strtolower($notification->message), 'berhasil')) {
                                $type = 'success';
                            }

                            // Set icon based on type
                            $icon = 'bx-info-circle';
                            if ($type === 'danger') {
                                $icon = 'bx-error-circle';
                            } elseif ($type === 'warning') {
                                $icon = 'bx-bell';
                            } elseif ($type === 'success') {
                                $icon = 'bx-check-circle';
                            }
                        @endphp

                        @if ($currentDate !== $notificationDate)
                            <li class="notification-date-group">
                                <i class="bx bx-calendar"></i> {{ $formattedDate }}
                            </li>
                            @php
                                $currentDate = $notificationDate;
                            @endphp
                        @endif

                        <li class="notification-item">
                            <div class="notification-icon">
                                <i class="bx {{ $icon }}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-message">
                                    {{ $notification->message }}

                                    @if ($type === 'danger')
                                        <span class="notification-badge badge-danger">Penting</span>
                                    @elseif($type === 'warning')
                                        <span class="notification-badge badge-warning">Perhatian</span>
                                    @elseif($type === 'success')
                                        <span class="notification-badge badge-success">Sukses</span>
                                    @endif
                                </div>
                                <div class="notification-time">
                                    <i class="bx bx-time"></i>
                                    {{ \Carbon\Carbon::parse($notification->created_at)->format('H:i') }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="notification-empty">
                    <div class="notification-empty-icon">
                        <i class="bx bx-bell-off"></i>
                    </div>
                    <h3 class="notification-empty-text">Tidak ada notifikasi</h3>
                    <p class="notification-empty-subtext">Semua notifikasi penting akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
