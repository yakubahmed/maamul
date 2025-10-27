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

    <!-- Chatbot widget -->
    <style>
      .chatbot-toggle{position:fixed;right:20px;bottom:20px;z-index:1050}
      .chatbot-panel{position:fixed;right:20px;bottom:80px;width:320px;max-height:460px;background:#fff;border:1px solid #e5e5e5;border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,.15);display:none;z-index:1050;overflow:hidden}
      .chatbot-header{background:#346cb0;color:#fff;padding:10px 12px;display:flex;align-items:center;justify-content:space-between}
      .chatbot-body{padding:10px;height:300px;overflow:auto}
      .chatbot-input{display:flex;gap:8px;padding:8px;border-top:1px solid #eee}
      .chat-msg{margin:6px 0;padding:8px 10px;border-radius:8px;max-width:85%}
      .chat-user{background:#e9f2ff;margin-left:auto}
      .chat-bot{background:#f6f7f9}
      .chat-links a{display:inline-block;margin-right:6px;margin-top:6px}
    </style>
    <div class="chatbot-toggle">
      <button id="chatbot_btn" class="btn btn-primary shadow rounded-circle" style="width:56px;height:56px"><i class="fa fa-comments"></i></button>
    </div>
    <div id="chatbot_panel" class="chatbot-panel">
      <div class="chatbot-header">
        <span><i class="fa fa-robot mr-1"></i> Assistant</span>
        <button class="btn btn-sm btn-light" id="chatbot_close"><i class="fa fa-times"></i></button>
      </div>
      <div class="chatbot-body" id="chatbot_body">
        <div class="chat-msg chat-bot">Hi! Ask me things like "current balance", "today sales", or type "reports".</div>
      </div>
      <div class="chatbot-input">
        <input type="text" id="chatbot_input" class="form-control" placeholder="Type a message..." autocomplete="off">
        <button id="chatbot_send" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
      </div>
    </div>
    <script>
      (function(){
        var panel = $('#chatbot_panel');
        $('#chatbot_btn').on('click', function(){ panel.toggle(); if(panel.is(':visible')){ $('#chatbot_input').focus(); }});
        $('#chatbot_close').on('click', function(){ panel.hide(); });
        function appendMsg(text, cls){ $('#chatbot_body').append($('<div>').addClass('chat-msg '+cls).text(text)); $('#chatbot_body').scrollTop($('#chatbot_body')[0].scrollHeight); }
        function appendLinks(links){ if(!links||!links.length) return; var wrap = $('<div class="chat-links chat-msg chat-bot"></div>'); links.forEach(function(l){ wrap.append('<a class="btn btn-sm btn-outline-primary" target="_blank" href="'+l.url+'">'+l.label+'</a>'); }); $('#chatbot_body').append(wrap); $('#chatbot_body').scrollTop($('#chatbot_body')[0].scrollHeight); }
        function send(){ var msg = $('#chatbot_input').val().trim(); if(!msg) return; appendMsg(msg,'chat-user'); $('#chatbot_input').val('');
          $.ajax({ url: '<?= BASE_URL ?>jquery/chatbot.php', type:'post', dataType:'json', data:{message: msg}, success: function(res){ if(res && res.reply){ appendMsg(res.reply,'chat-bot'); appendLinks(res.links); } else { appendMsg('Sorry, something went wrong.','chat-bot'); } }, error: function(){ appendMsg('Network error. Please try again.','chat-bot'); } });
        }
        $('#chatbot_send').on('click', send);
        $('#chatbot_input').on('keypress', function(e){ if(e.which === 13){ send(); }});
      })();
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
