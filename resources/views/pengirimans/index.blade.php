@extends('layouts.main')

@section('content')
<div class="p-3">
        <!-- Tombol Add Pengiriman di bagian paling atas kanan -->
        {{-- <div class="d-flex justify-content-end p-2">
            <a href="{{ route('pengirimans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Tambah
            </a>
        </div> --}}

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
                <h3 class="card-title">List Pengiriman</h3>
                <div class="card-tools">
                    <form action="{{ route('pengirimans.index') }}" class="input-group input-group-sm" method="GET" class="mb-3">
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
                            <th>Nomor</th>
                            <th>Kota</th>
                            <th>Kecamatan</th>
                            <th>User Kurir</th>
                            <th>Tanggal</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengirimans as $index => $pengiriman)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pengiriman->nomor }}</td>
                                <td>{{ $pengiriman->area_nama }}</td>
                                <td>{{ $pengiriman->subarea_nama }}</td>
                                <td>{{ $pengiriman->username }}</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($pengiriman->created_at)) }}</td>
                                <td>
                                    <button  type="button" class="btn btn-primary btn-sm detail-btn" data-id="{{ $pengiriman->id }}" data-toggle="modal" data-target="#modal-xl">
                                        Detail
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm download-btn" data-id="{{ $pengiriman->id }}">
                                        Cetak
                                    </button>
                                    <form action="{{ route('pengirimans.destroy', $pengiriman->id) }}" method="POST"
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

            <!-- Pagination Links -->
            <div class="d-flex justify-content-end pt-2 pr-2">
                {{ $pengirimans->appends(['search' => $search])->links() }}
            </div>
        </div>
        <!-- /.card -->

        <!-- /.modal -->
        <div class="modal fade" id="modal-xl">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Detail Pengiriman</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No STT</th>
                                    <th>Kecamatan</th>
                                </tr>
                            </thead>
                            <tbody id="modal-detail-body">
                                <!-- Konten detail akan dimasukkan di sini via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
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
                            // Submit form jika pengiriman mengkonfirmasi penghapusan
                            this.closest('form').submit();
                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const detailButtons = document.querySelectorAll('.detail-btn');

            detailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const pengirimanId = this.getAttribute('data-id');

                    // Tampilkan loading indikator
                    const modalBody = document.getElementById('modal-detail-body');
                    modalBody.innerHTML = `<tr><td colspan="3" class="text-center">Loading...</td></tr>`;

                    // Lakukan permintaan AJAX
                    fetch(`/pengirimans/${pengirimanId}/details`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const details = data.details;
                                modalBody.innerHTML = '';

                                details.forEach((detail, index) => {
                                    modalBody.innerHTML += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${detail.no_stt}</td>
                                            <td>${detail.subarea_nama}</td>
                                        </tr>
                                    `;
                                });
                            } else {
                                modalBody.innerHTML = `<tr><td colspan="3" class="text-center">Data tidak ditemukan</td></tr>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            modalBody.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Terjadi kesalahan</td></tr>`;
                        });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const downloadButtons = document.querySelectorAll('.download-btn');

            downloadButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const pengirimanId = this.dataset.id;

                    // Tampilkan konfirmasi sebelum download
                    Swal.fire({
                        title: 'Konfirmasi Download',
                        text: "Apakah Anda yakin ingin mendownload file Excel ini?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Download!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect ke route untuk download Excel
                            window.location.href = `/pengirimans/${pengirimanId}/export`;
                        }
                    });
                });
            });
        });
</script>

    </script>
    </div>
@endsection
