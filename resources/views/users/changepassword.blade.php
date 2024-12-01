@extends('layouts.main')

@section('content')
        <div class="card p-3">
            <div class="card-header">
                <h3>Change Passowrd</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('users.changePassword', $user->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="password_lama">Password lama</label>
                        <input type="password" name="password_lama" class="form-control" placeholder="Masukkan Password lama">
                        @if ($errors->has('password_lama'))
                            <span class="text-danger">{{ $errors->first('password_lama') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password baru">
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Masukkan Konfirmasi Password" class="form-control">
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-2">Update</button>
                        <a href="/users" class="btn btn-danger">Batal</a>
                    </div>
                </form>
            </div>
        </div>
        @if (session()->has('error'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
@endsection
