<?php
$path = "/archiveiot";

use app\controller\ThreatController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../vendor/autoload.php';
$page = "overview";
$threatCount = 0;
session_start();
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 8) {


    $controller = new ThreatController();


    if (isset($_POST["btnCreateReport"])) {
        $threatTitle = $_POST["threatTitle"];
        $threatType = $_POST["threatType"];
        $dateDetected = $_POST["dateDetected"];
        $severity = $_POST["severity"];
        $affectedDevices = $_POST["affectedDevices"];
        $iocs = $_POST["iocs"];
        $description = $_POST["description"];
        $mitigationSteps = $_POST["mitigationSteps"];
        $attachments = $_FILES["attachments"];


        if (strlen(trim($threatTitle)) > 4 && strlen(trim($affectedDevices)) > 2 && strlen(trim($iocs)) > 3 && strlen(trim($description)) > 10) {



            $payload = array(
                '_title' => $threatTitle,
                '_user_id' => $_SESSION["user"]["user_id"],
                '_desc' => $description,
                '_category' => $threatType,
                '_discovered' => $dateDetected,
                '_affected_devices' => $affectedDevices,
                '_severity_level' => $severity,
                '_iocs' => $iocs,
                '_mitigation_steps' => $mitigationSteps
            );


            $controller = new ThreatController();

            $response = json_decode($controller->addThreat($payload, $attachments), true);

            if (isset($response["message"]) && strlen(trim($response["message"])) > 4) {
                echo "<script> alert('" . $response["message"] . "'); </script>";
            }
        } else {
            echo "<script> alert('Ensure to fill the form field correctly!'); </script>";
        }
    }

    $threatCount = $controller->getMyCount((int) $_SESSION["user"]['user_id']);
} else {
    header("location: ./login.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Threat - ArchiveIot</title>
    <link rel="stylesheet" href="../static/styles/dashboard.css">

    <script type="text/javascript" charset="utf8" src="../static/libraries/jQuery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../static/summernote-lite.min.css">
    <script type="text/javascript" charset="utf8" src="../static/summernote-lite.min.js"></script>

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
                        <a href="./new-threat.php" class="active">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
                            </svg>
                            <span>Report Threat</span>
                        </a>
                    </li>
                    <li>
                        <a href="./threats.php">
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
                    <h1>Report Threat</h1>
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

            <section id="page_title">
                <h2>New threat report form</h2>
                <p>
                    <b>Total Report: </b> <?php echo $threatCount; ?>
                </p>
            </section>

            <section id="create_threat">

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="formControl">
                        <label for="threatTitle">Threat Title</label>
                        <input type="text" class="form-control" id="threatTitle" name="threatTitle" required>
                    </div>

                    <div class="formControl">
                        <label for="threatType">Threat Type</label>
                        <select class="form-control" id="threatType" name="threatType" required>
                            <option value="malware">Malware</option>
                            <option value="ddos">DDoS</option>
                            <option value="unauthorized_access">Unauthorized Access</option>
                        </select>
                    </div>
                    <div class="formControl">
                        <label for="dateDetected">Date Detected</label>
                        <input type="date" class="form-control" id="dateDetected" name="dateDetected" required>
                    </div>

                    <div class="formControl">
                        <label for="severity">Severity</label>
                        <select class="form-control" id="severity" name="severity" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="formControl">
                        <label for="affectedDevices">Affected Devices</label>
                        <textarea class="form-control" id="affectedDevices" name="affectedDevices" rows="3" required></textarea>
                    </div>
                    <div class="formControl">
                        <label for="iocs">Indicators of Compromise (IoCs)</label>
                        <textarea class="form-control" id="iocs" name="iocs" rows="3" required></textarea>
                    </div>
                    <div class="formControl">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="formControl">
                        <label for="mitigationSteps">Mitigation Steps</label>
                        <textarea class="form-control" id="mitigationSteps" name="mitigationSteps" rows="3"></textarea>
                    </div>
                    <div class="formControl">
                        <label for="attachments">Attachments</label>
                        <input type="file" class="form-control-file" id="attachments" name="attachments">
                    </div>

                    <div class="formControl">
                        <button type="submit" name="btnCreateReport">Create Report</button>
                    </div>
                </form>
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

    $('#description').summernote({
        placeholder: 'start typing...',
        tabsize: 2,
        height: 160,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            // ['insert', ['link', 'picture', 'video']],
            ['view', ['codeview', 'help']]
        ]
    });

    $('#mitigationSteps').summernote({
        placeholder: 'start typing...',
        tabsize: 2,
        height: 160,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            // ['insert', ['link', 'picture', 'video']],
            ['view', ['codeview', 'help']]
        ]
    });
</script>

</html>