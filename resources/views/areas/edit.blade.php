@extends('layouts.main')

@section('content')
        <div class="card p-3">
            <div class="card-header">
                <h3>Ubah Kota</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('areas.update', $area->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="area_nama">Nama Kota</label>
                        <input type="text" name="area_nama" class="form-control" value="{{ $area->area_nama }}" placeholder="Masukkan Nama Kota" required>
                        @if ($errors->has('area_nama'))
                            <span class="text-danger">{{ $errors->first('area_nama') }}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-2">Perbarui</button>
                        <a href="/areas" class="btn btn-danger">Batal</a>
                    </div>
                </form>
            </div>
        </div>
@endsection
