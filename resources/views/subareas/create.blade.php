@extends('layouts.main')

@section('content')
        <div class="card p-3">
            <div class="card-header">
                <h3>Tambah Subarea</h3>
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
                <form action="{{ route('subareas.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="area_id">Kota</label>
                        <select id="area_id" name="area_id" class="form-control select2" style="width: 100%;" required>
                            <option value="">Pilih Kota</option>
                            @foreach($data_area as $area)
                                <option value="{{ $area->id }}">{{ $area->area_nama }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('area_id'))
                            <span class="text-danger">{{ $errors->first('area_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="subarea_nama">Nama Kecamatan</label>
                        <input type="text" name="subarea_nama" class="form-control" value="{{ old('subarea_nama') }}" placeholder="Masukkan Nama Kecamatan" required>
                        @if ($errors->has('subarea_nama'))
                            <span class="text-danger">{{ $errors->first('subarea_nama') }}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-2">Tambah</button>
                        <a href="/subareas" class="btn btn-danger">Batal</a>
                    </div>

                </form>
            </div>
        </div>
@endsection
