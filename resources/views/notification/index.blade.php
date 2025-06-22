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
            transition: all 0.3s ease;
            position: relative;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .notification-item.clickable {
            cursor: pointer;
        }

        .notification-item.clickable:hover {
            background-color: #f0fdfa;
            border-left: 4px solid #149d80;
        }

        .notification-item.read {
            opacity: 0.7;
        }

        .notification-item.unread {
            background-color: #f9fafb;
            border-left: 4px solid #149d80;
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

        .notification-icon.danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .notification-icon.warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .notification-icon.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
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

        .badge-unread {
            background-color: #149d80;
            color: white;
        }

        .clickable-hint {
            font-size: 0.75rem;
            color: #64748b;
            opacity: 0;
            transition: opacity 0.3s ease;
            margin-top: 0.25rem;
        }

        .notification-item.clickable:hover .clickable-hint {
            opacity: 1;
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

                            // Determine notification type based on message content
                            $type = 'info';
                            if (str_contains(strtolower($notification->message), 'kadaluwarsa')) {
                                $type = 'danger';
                            } elseif (
                                str_contains(strtolower($notification->message), 'hampir') ||
                                str_contains(strtolower($notification->message), 'menipis')
                            ) {
                                $type = 'warning';
                            } elseif (str_contains(strtolower($notification->message), 'berhasil')) {
                                $type = 'success';
                            } elseif (str_contains(strtolower($notification->message), 'habis')) {
                                $type = 'danger';
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

                            // Check if notification is clickable
                            $isClickable = $notification->stock && $notification->stock->masterStock;
                        @endphp

                        @if ($currentDate !== $notificationDate)
                            <li class="notification-date-group">
                                <i class="bx bx-calendar"></i> {{ $formattedDate }}
                            </li>
                            @php
                                $currentDate = $notificationDate;
                            @endphp
                        @endif

                        <li class="notification-item {{ $isClickable ? 'clickable' : '' }} {{ $notification->read ? 'read' : 'unread' }}"
                            @if ($isClickable) onclick="handleNotificationClick({{ $notification->id }})"
                                data-notification-id="{{ $notification->id }}" @endif>
                            <div class="notification-icon {{ $type }}">
                                <i class="bx {{ $icon }}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-message">
                                    {{ $notification->message }}

                                    @if (!$notification->read)
                                        <span class="notification-badge badge-unread">Baru</span>
                                    @endif

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
                                @if ($isClickable)
                                    <div class="clickable-hint">
                                        <i class="bx bx-mouse"></i> Klik untuk melihat detail stok
                                    </div>
                                @endif
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

@section('script')
    <script>
        function handleNotificationClick(notificationId) {
            // Mark notification as read and get redirect URL
            fetch(`/notifications/${notificationId}/markAsRead`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.redirect_url) {
                        // Update notification appearance
                        const notificationElement = document.querySelector(
                        `[data-notification-id="${notificationId}"]`);
                        if (notificationElement) {
                            notificationElement.classList.remove('unread');
                            notificationElement.classList.add('read');

                            // Remove "Baru" badge
                            const badge = notificationElement.querySelector('.badge-unread');
                            if (badge) {
                                badge.remove();
                            }
                        }

                        // Redirect to the stock batches page
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Fallback - just mark as read without redirect
                    const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (notificationElement) {
                        notificationElement.classList.remove('unread');
                        notificationElement.classList.add('read');
                    }
                });
        }

        // Add hover effect for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification-item.clickable');

            notifications.forEach(notification => {
                notification.style.cursor = 'pointer';
            });
        });
    </script>
@endsection
