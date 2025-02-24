 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="{{ url('build/css/bootstrap.min.css') }}">

 <!-- Datetimepicker CSS -->
 <link rel="stylesheet" href="{{ url('build/plugins/daterangepicker/daterangepicker.css') }}">
 <link rel="stylesheet" href="{{ url('build/css/bootstrap-datetimepicker.min.css') }}">

 <!-- animation CSS -->
 <link rel="stylesheet" href="{{ url('build/css/animate.css') }}">

 <!-- Select2 CSS -->
 <link rel="stylesheet" href="{{ url('build/plugins/select2/css/select2.min.css') }}">

 <!-- Fontawesome CSS -->
 <link rel="stylesheet" href="{{ url('build/plugins/fontawesome/css/fontawesome.min.css') }}">
 <link rel="stylesheet" href="{{ url('build/plugins/fontawesome/css/all.min.css') }}">

 <!-- Feathericon CSS -->
 <link rel="stylesheet" href="{{ url('build/css/feather.css') }}">

 <!-- Fancybox -->
 <link rel="stylesheet" href="{{url('build/plugins/fancybox/jquery.fancybox.min.css')}}">

 <!-- Summernote CSS -->
 <link rel="stylesheet" href="{{ url('build/plugins/summernote/summernote-bs4.min.css') }}">

 <!-- Bootstrap Tagsinput CSS -->
 <link rel="stylesheet" href="{{ url('build/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">

 <!-- Datatable CSS -->
 <link rel="stylesheet" href="{{ url('build/css/dataTables.bootstrap5.min.css') }}">

 <!-- Mobile CSS-->
 <link rel="stylesheet" href="{{ url('build/plugins/intltelinput/css/intlTelInput.css') }}">
 <link rel="stylesheet" href="{{ url('build/plugins/intltelinput/css/demo.css') }}">

 <link rel="stylesheet" href="{{ url('build/css/plyr.css') }}">

 <!-- Owl Carousel -->
 <link rel="stylesheet" href="{{ url('build/css/owl.carousel.min.css') }}">

 @if (Route::is(['sales-dashboard']))
     <!-- Map CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/jvectormap/jquery-jvectormap-2.0.5.css') }}">
 @endif

 @if (Route::is(['calendar']))
     <!-- Full Calander CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/fullcalendar/fullcalendar.min.css') }}">
 @endif

 <!-- Swiper CSS -->
 <link rel="stylesheet" href="{{ url('build/plugins/swiper/swiper.min.css') }}">

 <!-- Boxicons CSS -->
 <link rel="stylesheet" href="{{ url('build/plugins/boxicons/css/boxicons.min.css') }}">

 @if (Route::is(['ui-stickynote', 'ui-timeline']))
     <!-- Sticky CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/stickynote/sticky.css') }}">
 @endif

 @if (Route::is(['ui-scrollbar']))
     <link rel="stylesheet" href="{{ url('build/plugins/scrollbar/scroll.min.css') }}">
 @endif

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


 @if (Route::is(['ui-lightbox']))
     <!-- Lightbox CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/lightbox/glightbox.min.css') }}">
 @endif

 @if (Route::is(['ui-clipboard', 'ui-drag-drop']))
     <!-- Dragula CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/dragula/css/dragula.min.css') }}">
 @endif

 @if (Route::is(['icon-feather']))
     <!-- Feather CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/icons/feather/feather.css') }}">
 @endif

 @if (Route::is(['icon-flag']))
     <!-- Pe7 CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/icons/flags/flags.css') }}">
 @endif

 @if (Route::is(['icon-ionic']))
     <!-- Ionic CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/icons/ionic/ionicons.css') }}">
 @endif

 @if (Route::is(['icon-material']))
     <!-- Material CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/material/materialdesignicons.css') }}">
 @endif

 @if (Route::is(['icon-pe7']))
     <!-- Pe7 CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/icons/pe7/pe-icon-7.css') }}">
 @endif

 @if (Route::is(['icon-simpleline']))
     <!-- Simpleline CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/simpleline/simple-line-icons.css') }}">
 @endif

 @if (Route::is(['icon-themify']))
     <!-- Themify CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/icons/themify/themify.css') }}">
 @endif

 @if (Route::is(['icon-typicon']))
     <!-- Pe7 CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/icons/typicons/typicons.css') }}">
 @endif

 @if (Route::is(['icon-weather']))
     <!-- Pe7 CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/icons/weather/weathericons.css') }}">
 @endif

 @if (Route::is(['ui-rangeslider']))
     <!-- Rangeslider CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}">
 @endif

 @if (Route::is(['form-wizard']))
     <!-- Wizard CSS -->
     <link rel="stylesheet" href="{{ url('build/plugins/twitter-bootstrap-wizard/form-wizard.css') }}">
 @endif

 <!-- Main CSS -->
 {{-- <link rel="stylesheet" href="{{ url('build/css/style.css') }}"> --}}
 <link rel="stylesheet" href="{{ url('build/scss/main.css') }}">
