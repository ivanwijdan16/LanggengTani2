@extends('layouts.app')

@section('style')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
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

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
            width: 100%;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-top: none;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            color: #334155;
            border-color: #f1f5f9;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #149d80;
            border-color: #149d80;
            padding: 0.5rem 1rem;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #0c8b71;
            border-color: #0c8b71;
            box-shadow: 0 4px 10px rgba(0, 114, 79, 0.15);
        }

        .btn-warning {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background-color: #d97706;
            border-color: #d97706;
            color: white;
        }

        .btn-danger {
            background-color: #ef4444;
            border-color: #ef4444;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
        }

        .btn-secondary {
            background-color: #94a3b8;
            border-color: #94a3b8;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #64748b;
            border-color: #64748b;
            color: white;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .role-owner {
            background-color: #e0f2fe;
            color: #0284c7;
        }

        .role-employee {
            background-color: #f0fdf4;
            color: #16a34a;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        /* Modal styles */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.7);
            z-index: 1040;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-dialog {
            background-color: white;
            border-radius: 15px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
            margin: 1rem;
        }

        .modal-backdrop.show .modal-dialog {
            transform: translateY(0);
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            color: #1e293b;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .modal-title i {
            color: #ef4444;
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
            color: #475569;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .modal-username {
            font-weight: 600;
            color: #1e293b;
        }

        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .action-buttons form {
                width: 100%;
            }

            .action-buttons .btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bx bx-user-pin"></i> Daftar Pegawai
            </h1>
            <a href="{{ route('user.create') }}" class="btn btn-primary d-flex align-items-center">
                <i class="bx bx-plus me-1"></i> Tambah Pegawai
            </a>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <tr>
                                    <td><strong>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2"
                                                    style="background-color: #149d80; color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>{{ $user->name }}</div>
                                            </div>
                                        </strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span
                                            class="role-badge {{ $user->role === 'owner' ? 'role-owner' : 'role-employee' }}">
                                            <i class="bx {{ $user->role === 'owner' ? 'bx-crown' : 'bx-user' }} me-1"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @if ($user->role !== 'owner')
                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="btn btn-warning btn-sm d-flex align-items-center">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                    class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm d-flex align-items-center"
                                                        onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}')">
                                                        <i class="bx bx-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">Tidak dapat diubah</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="bx bx-user-x"></i>
                                        <h4>Belum ada data pegawai</h4>
                                        <p>Tambahkan pegawai baru dengan mengklik tombol di atas</p>
                                        <a href="{{ route('user.create') }}" class="btn btn-primary">
                                            <i class="bx bx-plus me-1"></i> Tambah Pegawai
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-error-circle"></i> Konfirmasi Hapus
                    </h5>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pegawai <span class="modal-username" id="deleteUserName"></span>?
                    </p>
                    <p>Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                        <i class="bx bx-x me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let currentDeleteForm = null;

        function openDeleteModal(userId, userName) {
            // Store the form for later submission
            currentDeleteForm = document.querySelector(
                    `.delete-form button[onclick="openDeleteModal('${userId}', '${userName}')"]`)
                .closest('form');

            // Update the modal content with the user's name
            document.getElementById('deleteUserName').textContent = userName;

            // Show the modal
            const modal = document.getElementById('deleteModal');
            modal.classList.add('show');

            // Prevent background scrolling
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            // Hide the modal
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('show');

            // Reset current form
            currentDeleteForm = null;

            // Re-enable background scrolling
            document.body.style.overflow = '';
        }

        // Set up the confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentDeleteForm) {
                currentDeleteForm.submit();
            }
            closeDeleteModal();
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && document.getElementById('deleteModal').classList.contains('show')) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
