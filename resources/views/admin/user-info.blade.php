@extends('layouts.admin')

@section('title', __('admin.pages.users'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/comments.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript">
        let route_profile_update = "{{ route('admin.user.update') }}";
        let route_comment_delete = "{{ route('admin.comment.delete') }}";
        let route_unlink_social  = "{{ route('admin.unlink.social') }}";
        let user_id = "{{ $user->id }}";
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
        <tr>
            <td>{{ __('GitHub') }}</td>
            <td>
                @if($user->entryPoints->where('type', \App\EntryPoint::GITHUB_REG)->first())
                    <div class="btn admin-social-unlink" social-type="{{ \App\EntryPoint::GITHUB_REG }}">{{ __('Unlink') }}</div>
                @else
                    {{ __('Not linked') }}
                @endif
            </td>
        </tr>
        <tr>
            <td>{{ __('Google') }}</td>
            <td>
                @if($user->entryPoints->where('type', \App\EntryPoint::GOOGLE_REG)->first())
                    <div class="btn admin-social-unlink" social-type="{{ \App\EntryPoint::GOOGLE_REG }}">{{ __('Unlink') }}</div>
                @else
                    {{ __('Not linked') }}
                @endif
            </td>
        </tr>
        <tr class="tr-button btn-edit">
            <td colspan="2">
                {{ __('Edit Info') }}
            </td>
        </tr>
        <tr style="display: none;" class="tr-button btn-save user-update">
            <td colspan="2">
                {{ __('Save') }}
            </td>
        </tr>
        <tr style="display: none;" class="tr-button btn-cancel">
            <td colspan="2">
                {{ __('Cancel editing') }}
            </td>
        </tr>
        @includeWhen($user->comments, 'admin.comment-list', ['comments_tree' => $user->comments->sortBy('id')])
    </table>
@endsection
