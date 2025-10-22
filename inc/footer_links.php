    <!-- BEGIN BASE JS -->
    <script src="<?= BASE_URL ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/vendor/popper.js/umd/popper.min.js"></script>
    <script src="<?= BASE_URL ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script> <!-- END BASE JS -->
    <!-- BEGIN PLUGINS JS -->
    <script src="<?= BASE_URL ?>assets/vendor/pace-progress/pace.min.js"></script>
    <script src="<?= BASE_URL ?>assets/vendor/stacked-menu/js/stacked-menu.min.js"></script>
    <script src="<?= BASE_URL ?>assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?= BASE_URL ?>assets/vendor/flatpickr/flatpickr.min.js"></script>
    <!-- <script src="<?= BASE_URL ?>assets/vendor/easy-pie-chart/jquery.easypiechart.min.js"></script> -->
    <script src="<?= BASE_URL ?>assets/vendor/chart.js/Chart.min.js"></script> <!-- END PLUGINS JS -->
    <script src="<?= BASE_URL ?>assets/javascript/pages/profile-demo.js"></script> <!-- END PAGE LEVEL JS -->
    <script src="<?= BASE_URL ?>assets/vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
    <!-- DataTables -->
    <script src="<?= BASE_URL ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= BASE_URL ?>assets/vendor/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= BASE_URL ?>assets/vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>

    <!-- BEGIN THEME JS -->
    <script src="<?= BASE_URL ?>assets/javascript/theme.min.js"></script> <!-- END THEME JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <!-- <script src="<?= BASE_URL ?>assets/javascript/pages/dashboard-demo.js"></script> END PAGE LEVEL JS -->
    <!-- SweetAlert2 -->
    <script src="<?= BASE_URL ?>assets/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="<?= BASE_URL ?>assets/toastr/toastr.min.js"></script>

    <script src="<?= BASE_URL ?>assets/vendor/bootstrap-session-timeout/bootstrap-session-timeout.min.js"></script> <!-- END PLUGINS JS -->
    
    <!-- Session Timeout Script (moved here after jQuery is loaded) -->
    <script src="<?= BASE_URL ?>assets/js/session-timeout.js"></script>

    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- <script src="<?= BASE_URL ?>assets/javascript/pages/chartjs-others-demo.js"></script> END PAGE LEVEL JS -->
    <script>
      $(function () {
        
        $("#example1").DataTable({
          "responsive": true,
          "autoWidth": true,
          "responsive": true,
          "ordering": false,
          "info": false,

          "select": true,
          "colReorder": false,

        });
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": false,
          "info": true,
          "autoWidth": true,
          "responsive": true,
        });
      });   
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-116692175-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-116692175-1');
    </script>

    <script>
      "use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

// Session Timeout Demo
// =============================================================
var SessionTimeoutDemo = /*#__PURE__*/function () {
  function SessionTimeoutDemo() {
    _classCallCheck(this, SessionTimeoutDemo);

    this.init();
  }

  _createClass(SessionTimeoutDemo, [{
    key: "init",
    value: function init() {
      // event handlers
      this.handleSessionTimeout();
    }
  }, {
    key: "handleSessionTimeout",
    value: function handleSessionTimeout() {
      $.sessionTimeout({
        message: 'You have been inactive for 30 minutes.',
        countdownMessage: 'Redirecting to logout in <span class="badge badge-warning">{timer}</span>',
        logoutUrl: '<?= BASE_URL ?>logout?redirect=<?= fullpath()  ?>',
        redirUrl: '<?= BASE_URL ?>logout?redirect=<?= fullpath() ?>',
        warnAfter: 1800000,
        redirAfter: 1860000,
        keepAlive: false,
        countdownSmart: true
      });
    }
  }]);

  return SessionTimeoutDemo;
}();
/**
 * Keep in mind that your scripts may not always be executed after the theme is completely ready,
 * you might need to observe the `theme:load` event to make sure your scripts are executed after the theme is ready.
 */


$(document).on('theme:init', function () {
  new SessionTimeoutDemo();
});
    </script>
  </body>

</html>

<?php 

function fullpath(){

  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
  $host = $_SERVER['HTTP_HOST'];
  $request_uri = $_SERVER['REQUEST_URI'];

  $full_url = $protocol . "://" . $host . $request_uri;

  return  $full_url;


}

?>