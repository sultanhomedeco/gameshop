@extends('layouts.app')
@section('title', 'Kelola Game')
@section('content')
<div class="container">
    <h1 class="mb-4">Kelola Game</h1>
    <a href="{{ route('admin.games.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Game
    </a>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Game</th>
                            <th>Deskripsi</th>
                            <th>Mata Uang</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($games as $game)
                        <tr>
                            <td>{{ $game->name }}</td>
                            <td>{{ $game->description }}</td>
                            <td>{{ $game->currency_name }}</td>
                            <td>
                                @if($game->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.games.edit', $game) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.games.delete', $game) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus game ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $games->links() }}</div>
        </div>
    </div>
</div>
@endsection 