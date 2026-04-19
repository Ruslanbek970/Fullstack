@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <h1 class="h4 mb-3">Пользователи</h1>

    <div class="table-responsive border border-secondary rounded">
        <table class="table table-dark table-sm mb-0">
            <thead><tr><th>ID</th><th>Email</th><th>Бан</th><th></th></tr></thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->banned_at ? $u->banned_at->format('Y-m-d H:i') : '—' }}</td>
                        <td class="text-end">
                            @can('user.ban')
                                @if($u->id !== auth()->id())
                                    @if($u->banned_at)
                                        <form action="{{ route('admin.users.unban', $u) }}" method="post" class="d-inline">@csrf<button class="btn btn-sm btn-outline-success">Разбан</button></form>
                                    @else
                                        <form action="{{ route('admin.users.ban', $u) }}" method="post" class="d-inline" onsubmit="return confirm('Забанить?');">@csrf<button class="btn btn-sm btn-outline-danger">Бан</button></form>
                                    @endif
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $users->links() }}</div>
@endsection
