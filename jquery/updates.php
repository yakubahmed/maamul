<?php 
include('../path.php');
include('../inc/session_config.php');
session_start();

include('../inc/config.php');

// Only allow logged-in users to check/apply updates
if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'not_logged_in'));
    exit;
}

date_default_timezone_set('Africa/Nairobi');

function hasNetworkConnectivity($host = 'github.com', $port = 443, $timeout = 3){
    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($fp){
        fclose($fp);
        return true;
    }
    return false;
}

function runGit($cmd){
    $cmdline = 'cd ' . escapeshellarg(ROOT_PATH) . ' && ' . $cmd . ' 2>&1';
    return shell_exec($cmdline);
}

function isGitRepo(){
    return is_dir(ROOT_PATH . DIRECTORY_SEPARATOR . '.git');
}

if(isset($_POST['check'])){
    header('Content-Type: application/json');

    if(!function_exists('shell_exec')){
        echo json_encode(array('error' => 'shell_disabled'));
        exit;
    }

    if(!isGitRepo()){
        echo json_encode(array('error' => 'not_git_repo'));
        exit;
    }

    $net = hasNetworkConnectivity();
    if(!$net){
        echo json_encode(array('error' => 'no_network'));
        exit;
    }

    // Ensure git is available
    $gitVersion = runGit('git --version');
    if(stripos($gitVersion, 'git version') === false){
        echo json_encode(array('error' => 'git_unavailable', 'message' => trim($gitVersion)));
        exit;
    }

    // Determine current branch
    $branch = trim(runGit('git rev-parse --abbrev-ref HEAD'));
    if($branch === ''){ $branch = 'master'; }

    // Fetch remotes
    runGit('git fetch origin --prune');

    $local = trim(runGit('git rev-parse HEAD'));
    $remoteRef = 'origin/' . $branch;
    $remote = trim(runGit('git rev-parse ' . escapeshellarg($remoteRef)));

    // Count ahead/behind
    $counts = trim(runGit('git rev-list --left-right --count ' . escapeshellarg($local . '...' . $remoteRef)));
    $ahead = 0; $behind = 0;
    if($counts !== ''){
        list($ahead, $behind) = array_map('intval', preg_split('/\s+/', $counts));
    }

    $commits = array();
    if($behind > 0){
        $log = runGit('git log ' . escapeshellarg($local . '..' . $remoteRef) . ' --pretty=format:%h%x09%s%x09%cr');
        $lines = array_filter(explode("\n", (string)$log));
        foreach($lines as $line){
            $parts = explode("\t", $line);
            $commits[] = array(
                'hash' => isset($parts[0]) ? $parts[0] : '',
                'subject' => isset($parts[1]) ? $parts[1] : '',
                'when' => isset($parts[2]) ? $parts[2] : ''
            );
        }
    }

    echo json_encode(array(
        'success' => true,
        'branch' => $branch,
        'local' => $local,
        'remote' => $remote,
        'ahead' => $ahead,
        'behind' => $behind,
        'commits' => $commits,
    ));
    exit;
}

if(isset($_POST['apply'])){
    header('Content-Type: application/json');

    if(!function_exists('shell_exec')){
        echo json_encode(array('error' => 'shell_disabled'));
        exit;
    }

    if(!isGitRepo()){
        echo json_encode(array('error' => 'not_git_repo'));
        exit;
    }

    $net = hasNetworkConnectivity();
    if(!$net){
        echo json_encode(array('error' => 'no_network'));
        exit;
    }

    // Pull latest
    $out = runGit('git pull 2>&1');
    $newHead = trim(runGit('git rev-parse HEAD'));
    echo json_encode(array('success' => true, 'output' => $out, 'head' => $newHead));
    exit;
}

// Default
header('Content-Type: application/json');
echo json_encode(array('error' => 'invalid_request'));
?>
