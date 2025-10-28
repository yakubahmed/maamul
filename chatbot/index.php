<?php
include('../path.php');
$title = 'Chat Assistant';
include(ROOT_PATH . '/inc/header.php');
?>
<body>
  <div class="app">
    <?php include(ROOT_PATH .'/inc/nav.php'); ?>
    <?php include(ROOT_PATH . '/inc/sidebar.php'); ?>
    <main class="app-main">
      <div class="wrapper">
        <div class="page">
          <div class="page-inner">
            <header class="page-title-bar">
              <div class="row align-items-center">
                <div class="col-md-8">
                  <h1 class="page-title"> <?= __t('Help Center') ?> / Chat Assistant</h1>
                </div>
                <div class="col-md-4 text-right">
                  <select id="chatLang" class="form-control">
                    <?php $currLang = $_SESSION['lang'] ?? 'so'; ?>
                    <option value="so" <?= ($currLang==='so'?'selected':'') ?>>Soomaali</option>
                    <option value="en" <?= ($currLang==='en'?'selected':'') ?>>English</option>
                  </select>
                </div>
              </div>
            </header>

            <div class="page-section">
              <div class="section-block">
                <div class="card">
                  <div class="card-body" style="max-width:900px">
                    <div id="chatWindow" style="height:420px; overflow:auto; border:1px solid #e5e5e5; border-radius:6px; padding:12px; background:#fafafa">
                      <!-- messages appear here -->
                    </div>

                    <div class="mt-3 d-flex">
                      <input id="chatInput" type="text" class="form-control mr-2" placeholder="Type a message... / Ku qor fariintaada...">
                      <button id="chatSend" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                    </div>
                    <small class="text-muted d-block mt-2">Try: "today sales", "low stock", "add item", "iib maanta", "kayd hooseeya"</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include(ROOT_PATH .'/inc/footer.php'); ?>
    </main>
  </div>
  <?php include(ROOT_PATH .'/inc/footer_links.php'); ?>

  <script>
    (function(){
      var $win = $('#chatWindow');
      function append(role, text, suggestions){
        var who = role === 'user' ? 'You' : 'Assistant';
        var bubbleColor = role === 'user' ? '#e7f1ff' : '#ffffff';
        var html = '<div class="mb-2">' +
                    '<div class="small text-muted">'+who+'</div>'+
                    '<div style="background:'+bubbleColor+'; display:inline-block; padding:8px 10px; border-radius:8px; max-width:100%">'+
                      $('<div/>').text(text).html()+
                    '</div>'+
                   '</div>';
        $win.append(html);
        if (suggestions && suggestions.length){
          var sugHtml = '<div class="mb-3">';
          suggestions.forEach(function(s){
            sugHtml += '<button class="btn btn-sm btn-outline-secondary mr-1 mb-1 chat-suggest">'+$('<div/>').text(s).html()+'</button>';
          });
          sugHtml += '</div>';
          $win.append(sugHtml);
        }
        $win.scrollTop($win[0].scrollHeight);
      }

      function send(){
        var msg = $('#chatInput').val().trim();
        if(!msg) return;
        var lang = $('#chatLang').val();
        append('user', msg);
        $('#chatInput').val('');
        $.ajax({
          url: 'assistant.php',
          method: 'POST',
          dataType: 'json',
          data: { message: msg, lang: lang },
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          success: function(res){
            append('assistant', res.reply || '', res.suggestions || []);
          },
          error: function(xhr){
            var text = 'Server error';
            if (xhr && xhr.responseJSON && xhr.responseJSON.error) text = xhr.responseJSON.error;
            append('assistant', text);
          }
        });
      }

      $('#chatSend').on('click', send);
      $('#chatInput').on('keydown', function(e){ if(e.key==='Enter'){ send(); }});
      $(document).on('click', '.chat-suggest', function(){ $('#chatInput').val($(this).text()); $('#chatInput').focus(); });

      // Greet on load
      setTimeout(function(){
        $.ajax({
          url: 'assistant.php',
          method: 'POST',
          dataType: 'json',
          data: { message: '__hello__', lang: ($('#chatLang').val() || 'so') },
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          success: function(res){ append('assistant', res.reply, res.suggestions); }
        });
      }, 300);
    })();
  </script>
</body>
</html>

