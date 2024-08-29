<?php
$path = "/archiveiot";

use app\controller\ThreatController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../vendor/autoload.php';
$page = "overview";
$threats = [];
$deleted_slug = [];

session_start();

if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 8) {

    if (isset($_SESSION["deleted_slugs"])) {
        $deleted_slug = $_SESSION["deleted_slugs"];
    } else {
        $_SESSION["deleted_slugs"] = [];
    }

    $controller = new ThreatController();


    if (isset($_GET["del"]) && strlen(trim($_GET["del"])) > 4 && in_array($_GET["del"], $deleted_slug) == false) {
        $response = json_decode($controller->deleteThreat($_GET["del"]), true);
        if (isset($response["message"]) && strlen(trim($response["message"])) > 4) {
            echo "<script> alert('" . $response["message"] . "'); </script>";
            array_push($_SESSION["deleted_slugs"], $_GET["del"]);
        }
    }

    $res = json_decode($controller->getMyThreatReports((int) $_SESSION["user"]['user_id']), true);
    $threats = $res['message'];
} else {
    header("location: ./login.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Threats - ArchiveIot</title>
    <link rel="stylesheet" href="../static/styles/dashboard.css">

    <link rel="stylesheet" type="text/css" href="../static/libraries/DataTables/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../static/libraries/DataTables/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="../static/libraries/jQuery/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="../static/libraries/DataTables/js/jquery.dataTables.js"></script>

</head>

<body>
    <div class="container">
        <aside>
            <div class="mobile-close" id="closeMenu">
                <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                </svg>
                <span>Close</span>
            </div>
            <header>
                <div class="profile">
                    <div class="alphaIcon">
                        <?php
                        if ($_SESSION["user"]["user_picture"] != NULL && strlen(trim($_SESSION["user"]["user_picture"])) > 8) {
                        ?>
                            <img src="<?php echo Helper::loadImage($_SESSION["user"]["user_picture"]); ?>" alt="<?php echo $_SESSION["user"]["user_fullname"]; ?>">
                        <?php
                        } else echo Helper::getInitialNames($_SESSION["user"]["user_fullname"]);
                        ?>
                    </div>
                    <div id="info">
                        <strong><?php echo $_SESSION["user"]["user_fullname"]; ?></strong>
                        <p><?php echo $_SESSION["user"]["user_email"]; ?></p>
                    </div>
                </div>
                <a href="./logout.php" title="Account Logout">
                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9"></path>
                    </svg>
                </a>
            </header>
            <menu>
                <small>Menu</small>
                <ul>
                    <li>
                        <a href="./">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="./search-threat.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            <span>Search Threat</span>
                        </a>
                    </li>
                    <li>
                        <a href="./new-threat.php">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
                            </svg>
                            <span>Report Threat</span>
                        </a>
                    </li>
                    <li>
                        <a href="./threats.php" class="active">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"></path>
                            </svg>
                            <span>My Threats</span>
                        </a>
                    </li>
                    <li>
                        <a href="./setting.php">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path>
                            </svg>
                            <span>Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="./logout.php">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9"></path>
                            </svg>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </menu>
        </aside>
        <main>
            <header>
                <div class="bars">
                    <svg id="openMenu" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
                    </svg>
                    <h1>My Threat List</h1>
                </div>
                <ul>
                    <li>
                        <a href="./notifications.php">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"></path>
                            </svg>
                            <span>Notification</span>
                        </a>
                    </li>
                    <li>
                        <a href="./logout.php">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9"></path>
                            </svg>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </header>

            <section id="table">
                <table id="post_table">
                    <thead>
                        <tr>
                            <th>Sn</th>
                            <th>Threat Title</th>
                            <th>Date uploaded</th>
                            <th>Last updated</th>
                            <th style="width: 160px;">...</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (count($threats) > 0) {
                            $count = 1;
                            foreach ($threats as $key => $threat) {
                        ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo $threat["threat_title"]; ?></td>
                                    <td><?php echo $threat["created_at"]; ?></td>
                                    <td><?php echo $threat["updated_at"]; ?></td>
                                    <td>
                                        <div class="controls">
                                            <button class="btnDeleteThreat" data-id='<?php echo $threat["threat_slug"]; ?>'>Delete</button>
                                            <a href="<?php echo $path . "/account/edit-threat.php?slug=" . $threat["threat_slug"]; ?>">Edit</a>
                                            <a href="<?php echo $path . "/account/threat.php?slug=" . $threat["threat_slug"]; ?>">View</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

</body>


<script>
    const closeMenu = document.querySelector("#closeMenu");
    const openMenu = document.querySelector("#openMenu");

    openMenu.addEventListener("click", function() {
        document.querySelector("aside").classList.toggle("showMenu");
    });
    closeMenu.addEventListener("click", function() {
        document.querySelector("aside").classList.toggle("showMenu");
    });

    $(document).ready(function() {
        $('#post_table').DataTable();
    });


    const btnDeleteThreat = document.querySelectorAll('.btnDeleteThreat');

    for (let i = 0; i < btnDeleteThreat.length; i++) {
        btnDeleteThreat[i].addEventListener('click', function(e) {
            const slug = e.target.getAttribute('data-id');
            if (confirm("Are you sure you want to delete this threat?")) {
                // alert(slug);
                window.location.href = `./threats.php?del=${slug}`;
            }
        })

    }
</script>

</html>