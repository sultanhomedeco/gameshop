@extends('layouts.app')
@section('title', 'Tambah Game')
@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Game</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.games.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Game</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="currency_name" class="form-label">Mata Uang</label>
                    <input type="text" class="form-control" id="currency_name" name="currency_name" required>
                </div>
                <div class="mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.games') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection 