<!-- FancyBox JS -->
{{-- <script src="{{ URL::asset('/build/plugins/fancybox/jquery.fancybox.min.js') }}"></script> --}}
<!-- Mobile Input -->
{{-- <script src="{{ URL::asset('/build/plugins/intltelinput/js/intlTelInput.js') }}"></script> --}}

{{-- <script src="{{ URL::asset('/build/js/plyr-js.js') }}"></script> --}}

<!-- Datatable JS -->
{{-- <script src="{{ URL::asset('/build/js/jquery.dataTables.min.js') }}"></script> --}}
{{-- <script src="{{ URL::asset('/build/js/dataTables.bootstrap5.min.js') }}"></script> --}}

<!-- Owl Carousel -->
{{-- <script src="{{ URL::asset('/build/js/owl.carousel.min.js') }}"></script> --}}

{{-- <!-- Map JS -->
 <script src="{{ URL::asset('/build/plugins/jvectormap/jquery-jvectormap-2.0.5.min.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/jvectormap/jquery-jvectormap-ru-mill.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/jvectormap/jquery-jvectormap-us-aea.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/jvectormap/jquery-jvectormap-uk_countries-mill.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/jvectormap/jquery-jvectormap-in-mill.js') }}"></script>
 <script src="{{ URL::asset('/build/js/jvectormap.js') }}"></script> --}}


<!-- Full Calendar JS -->
{{-- <script src="{{ URL::asset('/build/js/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('/build/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ URL::asset('/build/plugins/fullcalendar/jquery.fullcalendar.js') }}"></script> --}}

<!-- Swiper JS -->
{{-- <script src="{{ URL::asset('/build/plugins/swiper/swiper.min.js') }}"></script> --}}

<!-- Stickynote JS -->
{{-- <script src="{{ URL::asset('/build/plugins/countup/jquery.counterup.min.js') }}"></script>
    <script src="{{ URL::asset('/build/plugins/countup/jquery.waypoints.min.js') }}"></script>
    <script src="{{ URL::asset('/build/plugins/countup/jquery.missofis-countdown.js') }}"></script> --}}

<!-- Dragula JS -->
{{-- <script src="{{ URL::asset('/build/plugins/dragula/js/dragula.min.js') }}"></script>
    <script src="{{ URL::asset('/build/plugins/dragula/js/drag-drop.min.js') }}"></script>
    <script src="{{ URL::asset('/build/plugins/dragula/js/draggable-cards.js') }}"></script> --}}

<!-- Sticky-sidebar -->
{{-- <script src="{{ URL::asset('/build/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
<script src="{{ URL::asset('/build/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script> --}}


<!-- Internal Ratings JS -->
{{-- <script src="{{ URL::asset('/build/js/ratings.js') }}"></script> --}}


{{-- might need  --}}
<!-- Clipboard JS -->
{{-- <script src="{{ URL::asset('/build/plugins/clipboard/clipboard.min.js') }}"></script> --}}


<!-- Chart JS -->
{{-- <script src="{{ URL::asset('/build/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ URL::asset('/build/plugins/chartjs/chart-data.js') }}"></script> --}}

<!-- Wizard JS -->
{{-- <script src="{{ URL::asset('/build/plugins/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/twitter-bootstrap-wizard/prettify.js') }}"></script>
 <script src="{{ URL::asset('/build/plugins/twitter-bootstrap-wizard/form-wizard.js') }}"></script> --}}






<!-- jQuery -->
<script src="{{ URL::asset('/build/js/jquery-3.7.1.min.js') }}"></script>

<!-- Feather Icon JS -->
<script src="{{ URL::asset('/build/js/feather.min.js') }}"></script>

<!-- Slimscroll JS -->
<script src="{{ URL::asset('/build/js/jquery.slimscroll.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ URL::asset('/build/js/bootstrap.bundle.min.js') }}"></script>

<!-- Chart JS -->
<script src="{{ URL::asset('/build/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('/build/plugins/apexchart/chart-data.js') }}"></script>

<!-- Sweetalert 2 -->
<script src="{{ URL::asset('/build/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ URL::asset('/build/plugins/sweetalert/sweetalerts.min.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ URL::asset('/build/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('/build/js/custom-select2.js') }}"></script>

<!-- Datetimepicker JS -->
<script src="{{ URL::asset('/build/js/moment.min.js') }}"></script>
<script src="{{ URL::asset('/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ URL::asset('/build/plugins/daterangepicker/daterangepicker.js') }}"></script>

<!-- Bootstrap Tagsinput JS -->
<script src="{{ URL::asset('/build/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>

<!-- Summernote JS -->
<script src="{{ URL::asset('/build/plugins/summernote/summernote-bs4.min.js') }}"></script>

<script>
    function toast(msg, type = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: type,
            title: msg
        });
    }
    // session flash message
    @if (Session::has('success'))
        toast("{{ Session::get('success') }}", 'success');
    @elseif (Session::has('error'))
        toast("{{ Session::get('error') }}", 'error');
    @elseif (Session::has('info'))
        toast("{{ Session::get('info') }}", 'info');
    @elseif (Session::has('warning'))
        toast("{{ Session::get('warning') }}", 'warning');
    @endif
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<!-- Custom JS -->
<script src="{{ URL::asset('/build/js/theme-script.js') }}"></script>
<script src="{{ URL::asset('/build/js/script.js') }}"></script>
<script src="{{ URL::asset('build/js/custom-script.js') }}"></script>
