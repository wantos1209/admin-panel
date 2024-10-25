@extends('layouts.main')

@section('content')
<div class="p-3">
        <!-- Tombol Add Subarea di bagian paling atas kanan -->
        <div class="d-flex justify-content-end p-2">
            <a href="{{ route('subareas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Tambah
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
                <h3 class="card-title">List Kecamatan</h3>
                <div class="card-tools">
                    <form action="/subareas" class="input-group input-group-sm" style="width: 150px;">
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
                            <th width="100px">No</th>
                            <th>Kota</th>
                            <th>Kecamatan</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subareas as $index => $subarea)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subarea->area_nama }}</td>
                                <td>{{ $subarea->subarea_nama }}</td>
                                <td>
                                    <a href="{{ route('subareas.edit', $subarea->id) }}" class="btn btn-warning btn-sm">Ubah</a>
                                    <form action="{{ route('subareas.destroy', $subarea->id) }}" method="POST"
                                        class="delete-form" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-btn">Hapus</button>
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
                            // Submit form jika subarea mengkonfirmasi penghapusan
                            this.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
    </div>
@endsection
