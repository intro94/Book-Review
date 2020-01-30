@extends('layouts.admin')

@section('title', __('admin.pages.books'))

@section('content')
    <a href="{{ route('admin.book.new') }}" class="btn" style="margin: 0 auto; width: 300px; text-align: center;">{{ __('Add new book') }}</a>
    <div class="list big-list">
        @foreach(\App\Book::all()->sortBy('id') as $book)
            <a href="{{ route('admin.book.info', $book->id) }}" class="list-element">
                <div class="list-element-text">{{ $book->title }}</div>
                <div class="list-relationship-quantity">
                    <div class="quantity-number">
                        {{ $book->comments->count() }}
                    </div>
                    <div class="quantity-caption">{{ __('index.caption_comments') }}</div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
