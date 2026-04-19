@extends('layouts.app')

@section('title', __('site.nav_my_memes'))

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h1 class="h4 mb-0">{{ __('site.nav_my_memes') }}</h1>
        @can('meme.create')
            <a href="{{ route('memes.create') }}" class="btn btn-sm btn-warning">{{ __('site.nav_create_meme') }}</a>
        @endcan
    </div>

    <div class="table-responsive border border-secondary rounded">
        <table class="table table-striped mb-0 small align-middle">
            <thead><tr><th>#</th><th>{{ __('site.col_title') }}</th><th>{{ __('site.col_status') }}</th><th class="text-end">{{ __('site.col_actions') }}</th></tr></thead>
            <tbody>
                @forelse($memes as $m)
                    <tr>
                        <td>{{ $m->id }}</td>
                        <td>{{ $m->title }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $m->status }}</span>
                            @if($m->status === 'rejected' && $m->rejection_reason)
                                <div class="text-danger mt-1 small">{{ \Illuminate\Support\Str::limit($m->rejection_reason, 80) }}</div>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('memes.show', $m) }}" class="btn btn-sm btn-outline-info">{{ __('site.home_open') }}</a>
                            @can('meme.edit')
                                @if($m->user_id === auth()->id() || auth()->user()->hasRole(['admin','super-admin']))
                                    <a href="{{ route('memes.edit', $m) }}" class="btn btn-sm btn-outline-warning">{{ __('site.mine_edit') }}</a>
                                @endif
                            @endcan
                            @can('meme.delete')
                                @if($m->user_id === auth()->id() || auth()->user()->hasRole(['admin','super-admin']))
                                    <form action="{{ route('memes.destroy', $m) }}" method="post" class="d-inline" onsubmit="return confirm(@json(__('site.mine_delete_confirm', ['id' => $m->id])));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('site.mine_delete') }}</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">{{ __('site.mine_empty') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $memes->links() }}</div>
@endsection
