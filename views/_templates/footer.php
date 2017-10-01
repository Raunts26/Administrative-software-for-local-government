<!-- Main Footer -->

<footer class="main-footer">

    <!-- To the right ->

    <div class="pull-right hidden-xs">

      Rae haldustarkvara

    </div>

    <!-- Default to the left -->

    <a href="https://codeloops.ee"><img src="<?=$_SERVER['BASE_PATH'];?>/images/codeloops_mini.png" style="width: 100px;"></a>

    <!--<strong>Copyright &copy; 2015 <a href="#">Company</a>.</strong> All rights reserved.-->

</footer>


</div><!-- ./wrapper -->


<!-- REQUIRED JS SCRIPTS -->


<!-- jQuery 2.1.4 -->

<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/jQuery/jQuery-2.1.4.min.js"></script>

<!-- Bootstrap 3.3.5 -->

<script src="<?=$_SERVER['BASE_PATH'];?>/bootstrap/js/bootstrap.min.js"></script>

<!-- Moment ja et local -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/datetimepicker/build/js/et.js"></script>

<!-- AdminLTE App -->

<script src="<?=$_SERVER['BASE_PATH'];?>/dist/js/app.min.js"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/app.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/playground.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/properties.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/business.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/mobile.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/mobileedit.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/objects.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/tasks.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/service.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/inspection.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/calendar.js?v=<?=time();?>"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/js/it_support.js?v=<?=time();?>"></script>

<script src="<?=$_SERVER['BASE_PATH'];?>/js/autosize.min.js"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/iCheck/icheck.min.js"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/datepicker/bootstrap-datepicker.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/fullcalendar/fullcalendar.min.js"></script>
<!-- Daterange picker-->
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Datetimepicker -->
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?=$_SERVER['BASE_PATH'];?>/plugins/taginput/dist/bootstrap-tagsinput.min.js"></script>
<script>

    window.onload = function () {

        var app = new App();

        App.instance.usergroup = <?= json_encode($_SESSION['user_group'], JSON_HEX_TAG); ?>;
        autosize(document.querySelectorAll('textarea'));

        $('.status_filter').select2({
          sorter: function(data) {
              return data.sort(function(a, b) {
                  return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
              });
          }
        });
        $('.responsible_reader').select2();
        $('#select_rights').select2({
            placeholder: "Vali objekti õigused"
        });
        $('#select_rights_editing').select2();

        //Date picker
        $('.datepicker').datepicker({
            weekStart: 1,
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('.datepickertask').datepicker({
            weekStart: 1,
            todayHighlight: true,
            format: 'dd.mm.yyyy',
            autoclose: true
        });

        $(".datepickertask").datepicker("update", new Date());
        $(".datepicker").datepicker("update", new Date());

        $('#reserv_start').datetimepicker({
          locale: 'et',
          daysOfWeekDisabled: [0, 6]
        });
        $('#reserv_end').datetimepicker({
          locale: 'et',
          useCurrent: false, //Important! See issue #1075
          daysOfWeekDisabled: [0, 6]
        });
        $("#reserv_start").on("dp.change", function (e) {
          $('#reserv_end').data("DateTimePicker").minDate(e.date);
        });
        $("#reserv_end").on("dp.change", function (e) {
          $('#reserv_start').data("DateTimePicker").maxDate(e.date);
        });

        $('#reserv_start_edit').datetimepicker({
          locale: 'et',
          daysOfWeekDisabled: [0, 6]
        });
        $('#reserv_end_edit').datetimepicker({
          locale: 'et',
          useCurrent: false, //Important! See issue #1075
          daysOfWeekDisabled: [0, 6]
        });
        $("#reserv_start_edit").on("dp.change", function (e) {
          $('#reserv_end_edit').data("DateTimePicker").minDate(e.date);
        });
        $("#reserv_end_edit").on("dp.change", function (e) {
          $('#reserv_start_edit').data("DateTimePicker").maxDate(e.date);
        });

        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_square-red',
        });

        //Red color scheme for iCheck, this is bigger, replaced pink one with red2x
        $('input[type="checkbox"].minimal-pink, input[type="radio"].minimal-pink').iCheck({
            checkboxClass: 'icheckbox_minimal-pink',
            radioClass: 'iradio_square-pink',
        });
    };
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
   Both of these plugins are recommended to enhance the
   user experience. Slimscroll is required when using the
   fixed layout. -->
</body>
</html>
