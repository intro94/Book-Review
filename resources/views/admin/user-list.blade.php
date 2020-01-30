@extends('layouts.admin')

@section('title', __('admin.pages.users'))

@section('content')
    <div class="list big-list">
        @foreach(\App\User::all()->sortBy('id') as $user)
            <a href="{{ route('admin.user.info', $user->id) }}" class="list-element">
                <div class="list-element-text">{{ $user->first_name }} {{ $user->last_name }}</div>
                <div class="list-relationship-quantity">
                    <div class="quantity-number">
                        {{ $user->comments->count() }}
                    </div>
                    <div class="quantity-caption">{{ __('index.caption_comments') }}</div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
