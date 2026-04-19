@extends('layouts.app')

@section('title', 'О сайте — Мемы')

@section('content')
    <article class="mx-auto" style="max-width:640px">
        <h1 class="h3 mb-3">О проекте</h1>
        <p class="text-secondary">
            Это платформа для мемов: загрузка или ссылка, модерация, лайки и комментарии.
            Роли пользователей задаются через <strong>Spatie Laravel Permission</strong>.
        </p>
        <p class="text-secondary mb-0">
            Раздел «Статистика» доступен ролям с правом аналитики — там диаграммы по реальным данным из базы.
        </p>
    </article>
@endsection
