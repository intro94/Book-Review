@extends('layouts.app')

@section('title', __('main.pages.index'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/index.css') }}">
@endpush

@section('content')
    <div class="site-description">
        This is a "Book Review" site.
    </div>
    <div class="latest-entries">
        <div class="list twin-list">
            <div class="list-title">{{ __('index.latest_authors') }}</div>
            @forelse(\App\Author::all()->sortByDesc('id')->take(5) as $author)
                <a href="{{ route('author.info', $author->id) }}" class="list-element">
                    <div class="list-element-text">{{ $author->first_name }} {{ $author->last_name }}</div>
                    <div class="list-relationship-quantity">
                        <div class="quantity-number">
                            {{ $author->books->count() }}
                        </div>
                        <div class="quantity-caption">{{ __('index.caption_books') }}</div>
                    </div>
                </a>
                @empty
                    <div class="list-element">
                        <div class="list-element-text" style="width: 100%; text-align: center;">{{ __('No authors') }}</div>
                    </div>
                @endforelse
            <a href="{{ route('author.list') }}" class="full-list-link">&gt; {{ __('index.all_authors') }} &lt;</a>
        </div>
        <div class="list twin-list">
            <span class="list-title">{{ __('index.latest_books') }}</span>
            @forelse(\App\Book::all()->sortByDesc('id')->take(5) as $book)
                <a href="{{ route('book.info', $book->id) }}" class="list-element">
                    <div class="list-element-text">{{ $book->title }}</div>
                    <div class="list-relationship-quantity">
                        <div class="quantity-number">
                            {{ $book->comments->count() }}
                        </div>
                        <div class="quantity-caption">{{ __('index.caption_comments') }}</div>
                    </div>
                </a>
                @empty
                    <div class="list-element">
                        <div class="list-element-text" style="width: 100%; text-align: center;">{{ __('No books') }}</div>
                    </div>
                @endforelse
            <a href="{{ route('book.list') }}" class="full-list-link">&gt; {{ __('index.all_books') }} &lt;</a>
        </div>
    </div>
@endsection
