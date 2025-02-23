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
    <title>Fast Auto Clinics</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon"
        href="https://scontent.fdac14-1.fna.fbcdn.net/v/t39.30808-1/414208409_122103875102160003_9223256422369593478_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=105&ccb=1-7&_nc_sid=2d3e12&_nc_ohc=_5lD4NlXr8oQ7kNvgFDfhlf&_nc_oc=AdiALp63nvIoFdQHDlZOdTwBS9nwG_0z2YZoB8VlycJB8nuAfYi7KFOhv1DXsoQNd90&_nc_zt=24&_nc_ht=scontent.fdac14-1.fna&_nc_gid=AfJSptb8xYm91Gx-HAOf9Jr&oh=00_AYDBQUdqU0iLrL7Hzq5_b43qQp64HGEldOVSElVSOSuPVQ&oe=67BA0491">

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

@stack('scripts')
</body>

</html>
