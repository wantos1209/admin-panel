@extends('layouts.main')

@section('content')
        <div class="card p-3">
            <div class="card-header">
                <h3>Tambah User Aplikasi</h3>
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
                <form action="{{ route('userapks.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="subarea_id">Kecamatan</label>
                        <select id="subarea_id" name="subarea_id" class="form-control select2" style="width: 100%;" required>
                            <option value="">Pilih Kecamatan</option>
                            @foreach($data_subarea as $subarea)
                                <option value="{{ $subarea->id }}">({{$subarea->area_nama}}) {{ $subarea->subarea_nama }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('subarea_id'))
                            <span class="text-danger">{{ $errors->first('subarea_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Masukkan Nama Kecamatan" required>
                        @if ($errors->has('username'))
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Masukkan Konfirmasi Password" required>
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-2">Tambah</button>
                        <a href="/userapks" class="btn btn-danger">Batal</a>
                    </div>

                </form>
            </div>
        </div>
@endsection
