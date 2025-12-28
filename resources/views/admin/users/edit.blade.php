@extends('layouts.main')

@section('title', $title)

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h4>Form Edit User</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <x-form-group label="Nama" for="name">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>
                        <x-form-group label="Email" for="email">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </x-form-group>
                        <x-form-group label="Role" for="role">
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
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
