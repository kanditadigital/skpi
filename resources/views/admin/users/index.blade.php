@extends('layouts.main')

@section('title', $title)

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                    <div class="card-header bg-primary">
                        <h4>Daftar User</h4>
                        <div class="card-header-action">
                            <x-button variant="primary" href="{{ route('admin.users.create') }}">
                                Tambah
                            </x-button>
                        </div>
                    </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <x-button size="sm" variant="secondary" href="{{ route('admin.users.edit', $user) }}">
                                            Edit
                                        </x-button>
                                        <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('POST')
                                            <x-button size="sm" variant="secondary" type="submit" onclick="return confirm('Password baru akan dikirimkan melalui email. Lanjutkan reset?')">
                                                Reset Password
                                            </x-button>
                                        </form>
                                        @if(!$user->hasRole('super_admin') && $user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <x-button size="sm" variant="danger" type="submit" onclick="return confirm('Hapus user ini?')">
                                                Hapus
                                            </x-button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
