@extends('layouts.main')

@section('content')
        <div class="card p-3">
            <div class="card-header">
                <h3>Edit User</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" placeholder="Masukkan Nama" required>
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" placeholder="Masukkan Username" required>
                        @if ($errors->has('username'))
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                        @endif
                    </div>
                    <p class="small mt-5 text-danger"> * Masukkan Password dan Konfirmasi Password jika ingin melakukan pergantian password saja.</p>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password">
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
@endsection
