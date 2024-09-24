<?php
session_start();

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
//var_dump($_ENV);  // Debugging step to check if the env variables are loaded
 


// Include database configuration
$dbConfig = require 'db.php';  // This loads the configuration array from db.php

// Include necessary files
require 'models/log.php';
require 'models/User.php';
require 'Controllers/AuthController.php';
require 'Controllers/HomeController.php';
require 'Controllers/LogController.php';  // Include LogController
require 'Controllers/PermissionsController.php';  // Include PermissionsController
require 'Controllers/DiscordController.php';  // Include DiscordController

// Database connection
$conn = new mysqli($dbConfig['servername'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize models and controllers
$userModel = new User($conn);
$authController = new AuthController($userModel);
$homeController = new HomeController($userModel);
$logController = new LogController($conn);  // LogController
$permissionsController = new PermissionsController($conn);  // PermissionsController
$discordController = new DiscordController($userModel);  // DiscordController

// Helper function to log actions
function log_action($logController, $action, $access_granted = 1) {
    if (isset($_SESSION['username'])) {
        // Log action for the current user
        $logController->logAccess($_SESSION['username'], $action, $access_granted);
    }
}

// Routing logic
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'login') {
    $authController->login();
    log_action($logController, 'login');  // Log login action
} elseif ($action === 'register') {
    $authController->register();
    log_action($logController, 'register');  // Log registration action
} elseif ($action === 'logout') {
    log_action($logController, 'logout');  // Log logout action
    $authController->logout();
} elseif ($action === 'secure') {
    log_action($logController, 'access_secure_page');  // Log secure page access
    $homeController->securePage();
} elseif ($action === 'logs') {
     log_action($logController, 'access_logs_page');
    // Ensure that only admins and moderators can access the logs page
    if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'moderator')) {
        $format = $_GET['format'] ?? 'table';  // Default format is table
        $ip_address = $_GET['ip'] ?? null;  // IP filtering, null by default
         // Fetch logs with format and IP filter
        require 'views/logs.php';  // Passings the logs and other params to the view
    } else {
        header('HTTP/1.0 403 Forbidden');
        echo "You do not have permission to access this page.";
        echo "Access Denied";
    }
} elseif ($action === 'permissions') {
    // Ensure only admins can access the permissions page
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        log_action($logController, 'access_permissions_page');  // Log access to permissions page
        $permissionsController->handlePermissionsPage();
    } else {
        header('HTTP/1.0 403 Forbidden');
        echo "You do not have permission to access this page.";
        echo "Access Denied";
        log_action($logController, 'access_permissions_page_denied', 0);  // Log denied access
    }
} elseif ($action === 'discord') {
    log_action($logController, 'access_discord_page'); 
    // Handle Discord OAuth login page
    $discord_token = $userModel->getDiscordToken($_SESSION['username']);
    $oauth_url = $discordController->generateOAuthUrl();

    if ($discord_token) {
        // If the user has a token, fetch and display their Discord info
        $discordData = $discordController->fetchDiscordUserInfo($discord_token);
        $userInfo = $discordData['userInfo'];
        $guilds = $discordData['guilds'];
        require 'views/discord.php';  // Shows Discord info view
    } else {
        require 'views/discord.php';  // Showing the "Link Discord Account" button
    }
} elseif ($action === 'discordCallback') {
    log_action($logController, 'access_Discord_loggedIn_page'); 
    // Handle Discord OAuth callback
    $discordController->handleCallback();
    header("Location: index.php?action=discord");  // Redirecting back to the Discord page
    exit();
} elseif ($action === 'disconnectDiscord') {
    $discordController->disconnectDiscord();
} else {
    echo "404 Not Found";
    log_action($logController, '404_not_found', 0);  // Log 404 error
}

// Close connection
$conn->close();
?>
