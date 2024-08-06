@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Admin Dashboard') }}</div>

                    <div class="card-body">
                        <p>Total Users: {{ $usersCount }}</p>
                        <p>Total Posts: {{ $postsCount }}</p>
                        <!-- Добавьте другую информацию и функциональность -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
