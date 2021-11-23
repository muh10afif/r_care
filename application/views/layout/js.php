<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="<?= base_url() ?>template/assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="<?= base_url() ?>template/assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="<?= base_url() ?>template/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!--This page plugins -->
<script src="<?= base_url() ?>template/assets/extra-libs/DataTables/datatables.min.js"></script>
<script src="<?= base_url() ?>template/dist/js/pages/datatable/datatable-basic.init.js"></script>
<!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script> -->
<!-- date picker -->
<script src="<?= base_url() ?>template/Cross-browser-Date-Time-Selector/date-time-picker.min.js"></script>
<!-- Morris Chart -->
<script src="<?= base_url() ?>template/assets/libs/morris.js/morris.min.js"></script>
<!-- apps -->
<script src="<?= base_url() ?>template/dist/js/app.min.js"></script>
<!-- minisidebar -->
<script>
$(function() {
    "use strict";
    $("#main-wrapper").AdminSettings({
        Theme: false, // this can be true or false ( true means dark and false means light ),
        Layout: 'vertical',
        LogoBg: 'skin1', // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6 
        NavbarBg: 'skin6', // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6
        SidebarType: 'mini-sidebar', // You can change it full / mini-sidebar / iconbar / overlay
        SidebarColor: 'skin1', // You can change the Value to be skin1/skin2/skin3/skin4/skin5/skin6
        SidebarPosition: false, // it can be true / false ( true means Fixed and false means absolute )
        HeaderPosition: false, // it can be true / false ( true means Fixed and false means absolute )
        BoxedLayout: false, // it can be true / false ( true means Boxed and false means Fluid ) 
    });
});
</script>
<script src="<?= base_url() ?>template/dist/js/app-style-switcher.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?= base_url() ?>template/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="<?= base_url() ?>template/assets/extra-libs/sparkline/sparkline.js"></script>
<!--Wave Effects -->
<script src="<?= base_url() ?>template/dist/js/waves.js"></script>
<!--Menu sidebar -->
<script src="<?= base_url() ?>template/dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="<?= base_url() ?>template/dist/js/custom.js"></script>
<!-- This Page JS -->
<script src="<?= base_url() ?>template/assets/libs/moment/moment.js"></script>
<script src="<?= base_url() ?>template/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Select2 -->
<script src="<?= base_url() ?>template/assets/libs/select2/dist/js/select2.full.min.js"></script>
<script src="<?= base_url() ?>template/assets/libs/select2/dist/js/select2.min.js"></script>
<script src="<?= base_url() ?>template/dist/js/pages/forms/select2/select2.init.js"></script>
<!-- chart -->
<script src="<?= base_url() ?>template/assets/libs/raphael/raphael.min.js"></script>
<script src="<?= base_url() ?>template/assets/libs/morris.js/morris.min.js"></script>
<script src="<?= base_url() ?>template/assets/libs/chart.js/dist/Chart.min.js"></script>

<!-- sweet alert -->
<script src="<?= base_url() ?>template/assets/swa/sweetalert2.all.min.js"></script>

<script>

    // var defaults = $.fn.datepicker.defaults = {
    //     autoclose: true,
    //     beforeShowDay: $.noop,
    //     calendarWeeks: false,
    //     clearBtn: false,
    //     daysOfWeekDisabled: [],
    //     endDate: Infinity,
    //     forceParse: true,
    //     format: 'mm/dd/yyyy',
    //     keyboardNavigation: true,
    //     language: 'en',
    //     minViewMode: 0,
    //     orientation: "auto",
    //     rtl: false,
    //     startDate: -Infinity,
    //     startView: 2,
    //     todayBtn: false,
    //     todayHighlight: false,
    //     weekStart: 0
    // };

    jQuery('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format      : "dd-MM-yyyy",
        orientation : "bottom"
    });

    jQuery('#date-range').datepicker({
        toggleActive: true,
        orientation : "bottom",
        autoclose   : true,
        format      : "MM-yyyy",
        viewMode    : "months",
        minViewMode : "months"
    });

    jQuery('#date-range-2').datepicker({
        toggleActive: true,
        orientation : "bottom",
        autoclose   : true,
        format      : "dd-MM-yyyy"
    });

    $(document).ready(function () {
        $('#tabel').DataTable();
    })

    $('#tanggal').dateTimePicker({

    // used to limit the date range
    limitMax: null, 
    limitMin: null, 

    // year name
    yearName: '',

    // month names
    monthName: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

    // day names
    dayName: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],

    // "date" or "dateTime"
    mode: 'date', 

    // custom date format
    format: null 

    });

    $('#tanggal2').dateTimePicker({

    // used to limit the date range
    limitMax: null, 
    limitMin: null, 

    // year name
    yearName: '',

    // month names
    monthName: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

    // day names
    dayName: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],

    // "date" or "dateTime"
    mode: 'date', 

    // custom date format
    format: null 

    });

</script>