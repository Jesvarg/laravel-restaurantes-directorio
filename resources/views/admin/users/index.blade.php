@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Usuarios</h2>
    </div>

    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Registrado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @can('update', $user)
                                            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="d-flex">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="form-select form-select-sm me-2" style="width: 120px;">
                                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Usuario</option>
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                                            </form>
                                        @else
                                            <span class="text-muted">No editable</span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center p-4">
                    <p class="mb-0">No se encontraron usuarios.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
