@extends('layouts.main')

@section('content')
        <div class="card p-3">
            <div class="card-header">
                <h3>Tambah Kota</h3>
            </div>
            <div class="card-body">

                <!-- Menampilkan pesan error validasi -->
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <form action="{{ route('areas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="area_nama">Nama Kota</label>
                        <input type="text" name="area_nama" class="form-control" value="{{ old('area_nama') }}" placeholder="Masukkan Nama Kota" required>
                        @if ($errors->has('area_nama'))
                            <span class="text-danger">{{ $errors->first('area_nama') }}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-2">Tambah</button>
                        <a href="/areas" class="btn btn-danger">Batal</a>
                    </div>

                </form>
            </div>
        </div>
@endsection
