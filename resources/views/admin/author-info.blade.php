@extends('layouts.admin')

@section('title', __('admin.pages.authors'))

@push('scripts')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript">
        let route_author_update = "{{ route('admin.author.update') }}";
        let route_author_delete = "{{ route('admin.author.delete') }}";
        let author_id = "{{ $author->id }}";
    </script>
@endpush

@section('content')
    <table style="max-width: 600px;">
        <tr>
            <td style="width: 100px;">{{ __('author.last_name') }}:</td>
            <td style="width: 494px;">
                <input id="last_name" class="edited-data" type="text" value="{{ $author->last_name }}" disabled>
            </td>
        </tr>
        <tr>
            <td>{{ __('author.first_name') }}:</td>
            <td>
                <input id="first_name" class="edited-data" type="text" value="{{ $author->first_name }}" disabled>
            </td>
        </tr>
        <tr>
            <td>{{ __('author.birth_date') }}:</td>
            <td>
                <input id="birthday" class="edited-data" type="date" value="{{ \Carbon\Carbon::parse($author->birth_date)->format('Y-m-d') }}" disabled>
            </td>
        </tr>
        <tr class="tr-button btn-edit">
            <td colspan="2">
                {{ __('Edit Info') }}
            </td>
        </tr>
        <tr style="display: none;" class="tr-button btn-save author-save">
            <td colspan="2">
                {{ __('Save') }}
            </td>
        </tr>
        <tr style="display: none;" class="tr-button btn-cancel">
            <td colspan="2">
                {{ __('Cancel editing') }}
            </td>
        </tr>
        <tr class="tr-button author-delete">
            <td colspan="2">{{ __('Delete this author') }}</td>
        </tr>
        <tr class="tr-button">
            <td colspan="2">
                <a style="display: block;" href="{{ route('admin.book.new', $author->id) }}">{{ __('Add new book') }}</a>
            </td>
        </tr>
    </table>
    <div style="width: 100%; margin: 10px 0; font-size: 18px; font-weight: bold;">{{ __('main.book_list') }}:</div>
    <div class="list big-list">
        @forelse($author->books->sortBy('id') as $book)
            <a href="{{ route('admin.book.info', $book->id) }}" class="list-element">
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
