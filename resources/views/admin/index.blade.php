@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Admin Dashboard') }}</div>

                    <div class="card-body">
                        <p>Admin account</p>
                    </div>

                    <div class="card-body">
                        <p>Total Users: {{ $usersCount }}</p>
                        <p>Total Posts: {{ $postsCount }}</p>
                        <!-- Добавьте другую информацию и функциональность -->
                        <!-- Вывод данных категории -->
                        <p>Category Title: {{ $categoryData['title'] }}</p>
                        <p>Category Description: {{ $categoryData['description'] }}</p>
                        <p>Category Content: {{ $categoryData['content'] }}</p>
                        <p>Category Images: {{ $categoryData['imgs'] }}</p>
                        <!-- Добавьте другие поля категории -->
                        <!-- Перебор массива $categoryData -->
                        @foreach($categoryData as $key => $value)
                            <p>{{ ucfirst($key) }}: {{ $value }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
