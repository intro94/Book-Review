@extends('layouts.app')

@section('title', __('main.pages.authors'))

@section('content')
    <table style="max-width: 600px;">
        <tr>
            <td style="width: 100px;">{{ __('author.first_name') }}:</td>
            <td style="width: 494px;">{{ $author->first_name }}</td>
        </tr>
        <tr>
            <td>{{ __('author.last_name') }}:</td>
            <td>{{ $author->last_name }}</td>
        </tr>
        <tr>
            <td>{{ __('author.birth_date') }}:</td>
            <td>{{ $author->birth_date }}</td>
        </tr>
    </table>
    <div style="width: 100%; margin: 10px 0; font-size: 18px; font-weight: bold;">{{ __('main.book_list') }}:</div>
    <div class="list big-list">
        @forelse($author->books->sortBy('id') as $book)
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
                <div class="list-element-text" style="width: 100%; text-align: center;">{{ __('Books not found') }}</div>
            </div>
        @endforelse
    </div>
@endsection
