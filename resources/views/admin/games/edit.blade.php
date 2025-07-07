@extends('layouts.app')
@section('title', 'Edit Game')
@section('content')
<div class="container">
    <h1 class="mb-4">Edit Game</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.games.update', $game) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Game</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $game->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description">{{ old('description', $game->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="currency_name" class="form-label">Mata Uang</label>
                    <input type="text" class="form-control" id="currency_name" name="currency_name" value="{{ old('currency_name', $game->currency_name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1" @if($game->is_active) selected @endif>Aktif</option>
                        <option value="0" @if(!$game->is_active) selected @endif>Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.games') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection 