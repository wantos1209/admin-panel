@extends('layouts.main')

@section('content')
    <div class="container">
        <!-- Tombol Add User di bagian paling atas kanan -->
        <div class="d-flex justify-content-end p-2">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Add User
            </a>
        </div>

        <!-- Notifikasi sukses -->
        @if (session('success'))
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User List</h3>
                <div class="card-tools">
                    <form action="/users" class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="search" value="{{ $search }}" class="form-control float-right"
                            placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        class="delete-form" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <script>
        // Saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Tangkap semua tombol delete
            const deleteButtons = document.querySelectorAll('.delete-btn');

            // Loop semua tombol delete dan tambahkan event listener
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Tampilkan konfirmasi dengan SweetAlert
                    Swal.fire({
                        title: 'Apakah kamu yakin?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form jika user mengkonfirmasi penghapusan
                            this.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
