@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Notifikasi</h1>
            @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('user.notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-check-double me-2"></i>Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($notifications->count())
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item {{ $notification->is_read ? '' : 'bg-light' }} border-0 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="{{ $notification->icon }} fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 {{ $notification->is_read ? 'text-muted' : 'fw-bold' }}">
                                                    {{ $notification->title }}
                                                </h6>
                                                <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                @if(!$notification->is_read)
                                                    <form action="{{ route('user.notifications.mark-read', $notification) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Tandai Dibaca">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('user.notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus notifikasi ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada notifikasi</h5>
                        <p class="text-muted">Anda akan menerima notifikasi ketika ada update transaksi atau promo menarik.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item {
    transition: background-color 0.2s ease-in-out;
}
.list-group-item:hover {
    background-color: #f8f9fa !important;
}
.list-group-item:not(:last-child) {
    border-bottom: 1px solid #dee2e6 !important;
}
</style>
@endsection 