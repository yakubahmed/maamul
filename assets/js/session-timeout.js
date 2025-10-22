// Session Timeout Management
// Displays warning modal after 30 minutes of inactivity
// Gives user 1 minute to extend session

class SessionTimeout {
    constructor() {
        this.warningTime = 30 * 60 * 1000; // 30 minutes in milliseconds
        this.countdownTime = 60 * 1000; // 1 minute countdown in milliseconds
        this.checkInterval = 30 * 1000; // Check every 30 seconds
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.countdownTimer = null;
        this.sessionTimer = null;
        this.isExtending = false;
        
        this.init();
    }
    
    init() {
        // Track user activity
        this.trackActivity();
        
        // Start the session timer
        this.startSessionTimer();
        
        // Check for activity every minute
        setInterval(() => {
            this.checkActivity();
        }, this.checkInterval);
    }
    
    trackActivity() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.lastActivity = Date.now();
                this.resetSessionTimer();
            }, true);
        });
    }
    
    checkActivity() {
        const now = Date.now();
        const timeSinceActivity = now - this.lastActivity;
        
        // If user has been inactive for 30 minutes and warning not shown
        if (timeSinceActivity >= this.warningTime && !this.warningShown) {
            this.showWarningModal();
        }
    }
    
    startSessionTimer() {
        this.sessionTimer = setTimeout(() => {
            this.showWarningModal();
        }, this.warningTime);
    }
    
    resetSessionTimer() {
        if (this.sessionTimer) {
            clearTimeout(this.sessionTimer);
        }
        
        if (this.warningShown) {
            this.hideWarningModal();
        }
        
        this.startSessionTimer();
    }
    
    showWarningModal() {
        this.warningShown = true;
        
        // Create modal HTML
        const modalHTML = `
            <div class="modal fade" id="sessionTimeoutModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">
                                <i class="fas fa-clock"></i> Session Timeout Warning
                            </h5>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="mb-3">Your Session is About to Expire!</h4>
                            <p class="mb-3">You have been inactive for 30 minutes.</p>
                            <p class="mb-2">Redirecting to logout in <span class="badge badge-warning" style="font-size: 1rem; padding: 5px 10px;"><span id="countdown-timer">1m 00s</span></span></p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" id="logoutNow">
                                Logout
                            </button>
                            <button type="button" class="btn btn-primary" id="extendSession">
                                Stay Connected
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Show modal
        $('#sessionTimeoutModal').modal('show');
        
        // Start countdown
        this.startCountdown();
        
        // Add event listeners
        document.getElementById('extendSession').addEventListener('click', () => {
            this.extendSession();
        });
        
        document.getElementById('logoutNow').addEventListener('click', () => {
            this.logout();
        });
    }
    
    startCountdown() {
        let timeLeft = this.countdownTime;
        
        this.countdownTimer = setInterval(() => {
            timeLeft -= 1000;
            
            const minutes = Math.floor(timeLeft / 60000);
            const seconds = Math.floor((timeLeft % 60000) / 1000);
            
            const display = `${minutes}m ${seconds.toString().padStart(2, '0')}s`;
            document.getElementById('countdown-timer').textContent = display;
            
            if (timeLeft <= 0) {
                this.logout();
            }
        }, 1000);
    }
    
    hideWarningModal() {
        if (this.countdownTimer) {
            clearInterval(this.countdownTimer);
        }
        
        $('#sessionTimeoutModal').modal('hide');
        $('#sessionTimeoutModal').remove();
        this.warningShown = false;
    }
    
    extendSession() {
        if (this.isExtending) {
            return; // Prevent multiple simultaneous requests
        }
        
        this.isExtending = true;
        
        // Disable the button temporarily
        const extendBtn = document.getElementById('extendSession');
        const originalText = extendBtn.innerHTML;
        extendBtn.disabled = true;
        extendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Extending...';
        
        // Send AJAX request to extend session
        $.ajax({
            url: 'jquery/extend-session.php',
            type: 'POST',
            data: {
                extend_session: true
            },
            success: function(response) {
                if (response === 'success') {
                    // Reset the session timer
                    this.lastActivity = Date.now();
                    this.resetSessionTimer();
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Session extended successfully!');
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Failed to extend session. Please try again.');
                    }
                }
            }.bind(this),
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error extending session. Please try again.');
                }
            },
            complete: function() {
                // Re-enable the button
                extendBtn.disabled = false;
                extendBtn.innerHTML = originalText;
                this.isExtending = false;
            }.bind(this)
        });
    }
    
    logout() {
        // Clear timers
        if (this.countdownTimer) {
            clearInterval(this.countdownTimer);
        }
        if (this.sessionTimer) {
            clearTimeout(this.sessionTimer);
        }
        
        // Redirect to logout
        window.location.href = 'logout.php';
    }
}

// Initialize session timeout when document is ready
$(document).ready(function() {
    // Only initialize if user is logged in (not on login page)
    if (!window.location.pathname.includes('login.php') && 
        !window.location.pathname.includes('forgot-password.php') &&
        !window.location.pathname.includes('reset-password.php') &&
        !window.location.pathname.includes('new-password.php')) {
        new SessionTimeout();
    }
});
