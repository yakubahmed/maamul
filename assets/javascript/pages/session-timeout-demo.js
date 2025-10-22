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
        logoutUrl: 'logout.php',
        redirUrl: 'login.php',
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