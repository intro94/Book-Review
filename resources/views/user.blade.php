@extends('layouts.app')

@section('title', __('main.pages.user'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/comments.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript">
        let route_profile_update = "{{ route('user.update') }}";
        let route_comment_delete = "{{ route('comment.delete') }}";
        let route_unlink_social  = "{{ route('user.unlink.social') }}";
    </script>
@endpush

@section('content')
    <table style="max-width: 600px;">
        <tr>
            <td style="width: 100px;">{{ __('Last name') }}:</td>
            <td style="font-weight: bold; width: 494px;">
                <input id="last_name" class="edited-data" type="text" value="{{ $user->last_name }}" disabled>
            </td>
        </tr>
        <tr>
            <td>{{ __('First name') }}:</td>
            <td style="font-weight: bold">
                <input id="first_name" class="edited-data" type="text" value="{{ $user->first_name }}" disabled>
            </td>
        </tr>
        <tr>
            <td>{{ __('E-mail') }}:</td>
            <td style="font-weight: bold">
                <input id="email" class="edited-data" type="email" value="{{ $user->email }}" disabled>
            </td>
        </tr>
        <tr>
            <td>{{ __('Birthday') }}:</td>
            <td style="font-weight: bold">
                <input id="birthday" class="edited-data" type="date" value="{{ \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') }}" disabled>
            </td>
        </tr>
        @if(auth()->user()->user->id == $user->id)
            <tr>
                <td>{{ __('GitHub') }}</td>
                <td>
                    @if($user->entryPoints->where('type', \App\EntryPoint::GITHUB_REG)->first())
                        <div class="btn social-unlink" social-type="{{ \App\EntryPoint::GITHUB_REG }}">{{ __('Unlink') }}</div>
                    @else
                        <a class="btn" href="{{ route('login.github') }}">{{ __('Link GitHub') }}</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>{{ __('Google') }}</td>
                <td>
                    @if($user->entryPoints->where('type', \App\EntryPoint::GOOGLE_REG)->first())
                        <div class="btn social-unlink" social-type="{{ \App\EntryPoint::GOOGLE_REG }}">{{ __('Unlink') }}</div>
                    @else
                        <a class="btn" href="{{ route('login.google') }}">{{ __('Link Google') }}</a>
                    @endif
                </td>
            </tr>
            <tr class="tr-button btn-edit">
                <td colspan="2">
                    {{ __('Edit Info') }}
                </td>
            </tr>
            <tr style="display: none;" class="tr-button btn-save profile-save">
                <td colspan="2">
                    {{ __('Save') }}
                </td>
            </tr>
            <tr style="display: none;" class="tr-button btn-cancel">
                <td colspan="2">
                    {{ __('Cancel editing') }}
                </td>
            </tr>
        @endif
        @includeWhen($user->comments, 'helpers.comment-list', ['comments_tree' => $user->comments->sortBy('id')])
    </table>
@endsection
