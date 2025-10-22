<?php
// Session configuration - must be included before session_start()
// Only set session settings if session hasn't been started yet

if (session_status() === PHP_SESSION_NONE) {
    // Session timeout configuration (30 minutes)
    ini_set('session.gc_maxlifetime', 1800);
    ini_set('session.cookie_lifetime', 1800);
    
    // Set session name for security
    ini_set('session.name', 'RAMAAS_SESSION');
    
    // Set secure session options
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
}
?>



