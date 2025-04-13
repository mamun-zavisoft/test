<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fast Auto Clinic</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon"
        href="#">

    @include('layout.partials.head')
</head>

@if (Route::is(['chat']))

    <body class="main-chat-blk">
@endif
@if (!Route::is(['register', 'login']))

    <body>
@endif

@if (Route::is(['register', 'login']))

    <body class="account-page">
@endif
{{-- @component('components.loader')
@endcomponent --}}
<!-- Main Wrapper -->
@if (!Route::is(['lock-screen']))
    <div class="main-wrapper">
@endif
@if (Route::is(['lock-screen']))
    <div class="main-wrapper login-body">
@endif
@if (!Route::is(['register', 'login']))
    @include('layout.partials.header')
@endif
@if (!Route::is(['register', 'login']))
    @include('layout.partials.sidebar')
@endif
@yield('content')
</div>
<!-- /Main Wrapper -->
{{-- @include('layout.partials.theme-settings') --}}
@include('layout.partials.footer-scripts')
<script>
    // Default image path
    const defaultUploadImagePath = '{{ asset('build/img/icons/upload.svg') }}';
    window.Laravel = {
        routes: {
            deleteMedia: '{{ route("admin.media.delete") }}'
        }
    };
</script>

@stack('scripts')
</body>

</html>
