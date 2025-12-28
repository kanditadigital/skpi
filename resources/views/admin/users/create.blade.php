@extends('layouts.main')

@section('title', $title)

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h4>Form Tambah User</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <x-form-group label="Nama" for="name" class="col-md-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </x-form-group>
                            <x-form-group label="Email" for="email" class="col-md-6">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </x-form-group>
                        </div>
                        <div class="form-group row">
                            <x-form-group label="Password" for="password" class="col-md-6">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </x-form-group>
                            <x-form-group label="Konfirmasi Password" for="password_confirmation" class="col-md-6">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </x-form-group>
                        </div>
                        <x-form-group label="Role" for="role">
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>
                        <div class="form-group">
                            <x-button type="submit" variant="primary">
                                Simpan
                            </x-button>
                            <x-button variant="danger" href="{{ route('admin.users.index') }}">
                                Batal
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
