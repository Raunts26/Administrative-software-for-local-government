<?php

//error_reporting(0);
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['BASE_PATH'] . "/views/login.php");
    exit();
}

if (isset($_SESSION['user_group'])) {

    if ($_SESSION['user_group'] !== "4") {
        if ($page_file === "kasutajad.php") {
            header("Location: " . $_SERVER['BASE_PATH'] . "/index.php");
            exit();
        } else if ($page_file === "mobile.php" || $page_file === "autod.php" || $page_file === "ruumid.php") {
            if ($_SESSION['user_group'] !== "2" && $_SESSION['user_group'] !== "3") {
                header("Location: " . $_SERVER['BASE_PATH'] . "/index.php");
                exit();
            }
        } else if ($page_file === "objektid.php" || $page_file === "pinnad.php" || $page_file === "valjakud.php" || $page_file === "hooldus.php" || $page_file === "hooldusadmin.php") {
            if ($_SESSION['user_group'] !== "3") {
                header("Location: " . $_SERVER['BASE_PATH'] . "/index.php");
                exit();
            }
        }


    }

} else if (!isset($_SESSION['user_group']) && $page_file !== "login.php") {
    header("Location: " . $_SERVER['BASE_PATH'] . "/views/login.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/dbconfig.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/class.user.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/objects.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/orgs.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/playgrounds.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/upload.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/properties.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/timetables.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/stats.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/service.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/inspection.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/calendar.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/tasks.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/it_support.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/management.class.php");

$database = "";

$mysqli = new mysqli($servername, $server_username, $server_password, $database);

$login = new USER();
$Objects = new Objects($mysqli);
$Orgs = new Orgs($mysqli);
$Playgrounds = new Playgrounds($mysqli);
$Upload = new Upload($mysqli);
$Properties = new Properties($mysqli);
$Timetable = new Timetable($mysqli);
$Stats = new Stats($mysqli);
$Service = new Service($mysqli);
$Inspection = new Inspection($mysqli);
$Calendar = new Calendar($mysqli);
$Tasks = new Tasks($mysqli);
$IT_support = new IT_support($mysqli);
$Management = new Management($mysqli);


include_once($_SERVER['DOCUMENT_ROOT'] . "/inc/action.php");

?>
