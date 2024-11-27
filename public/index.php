<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('BASEPATH', true);
session_start();

// Autoload classes
// require_once dirname(__DIR__) . '/vendor/autoload.php'; // If using Composer
// Or manually require necessary files
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/src/model/User.php';

$page = $_GET['page'] ?? "home";

switch ($page) {
    case "register":
        require_once dirname(__DIR__) . "/templates/auth/register.php";
        break;
    case 'login':
        require_once dirname(__DIR__) . '/templates/auth/login.php';
        break;
    case 'logout':
        require_once dirname(__DIR__) . '/templates/auth/logout.php';
        break;

    default:
        require_once dirname(__DIR__) . '/templates/dashboard.php';
        break;

}
