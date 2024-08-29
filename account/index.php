<?php
$path = "/archiveiot";

use app\controller\CommentController;
use app\controller\EngageController;
use app\controller\ThreatController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../vendor/autoload.php';
$total_report = 500;
$followers = 930;
$comments = 398;
$likes = 93893;
$o_report = 0;
$o_comments = 4930;
$o_followers = 3394;

$threatCount = 0;

session_start();
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 8) {

    $controller = new ThreatController();
    $engageController = new EngageController();
    $commentController = new CommentController();

    $o_report = $controller->getCount();
    $o_followers = $engageController->getCount();
    $o_comments = $commentController->getCount();

    $total_report = $controller->getMyCount((int) $_SESSION["user"]['user_id']);
    $followers = $engageController->userFollowerCount((int) $_SESSION["user"]['user_id']);
    $comments = $commentController->getMyCommentCount((int) $_SESSION["user"]['user_id']);
} else {
    header("location: ./login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ArchiveIot</title>
    <link rel="stylesheet" href="../static/styles/dashboard.css">
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
                        <a href="./" class="active">
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
                    <h1>Dashboard</h1>
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
                <div class="welcome">
                    <h1>Hi, <?php echo $_SESSION["user"]["user_fullname"] ?></h1>
                    <p>Welcome back to ArchiveIoT reporters dashboard</p>
                </div>
            </section>

            <section>
                <h2>My statistics</h2>
                <div id="statistic">
                    <div class="card-item">
                        <div class="card-body">
                            <div class="card-info">
                                <strong><?php echo number_format($total_report); ?></strong>
                                <p>Total reports</p>
                            </div>
                            <svg fill="#000000" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve">
                                <g>
                                    <g>
                                        <g>
                                            <path d="M442.069,46.675H234.646c-27.032,0-50.515,15.427-62.149,37.93h-35.789c-5.892,0-10.667,4.777-10.667,10.667v12.22
				c-15.333,4.423-29.7,17.915-37.105,35.791c-10.934,26.398-4.491,55.131,17.237,76.859c31.483,31.481,26.619,61.338,24.617,69.14
				L3.125,416.947C1.124,418.948,0,421.66,0,424.49c0,2.829,1.124,5.543,3.126,7.543l30.171,30.169
				c2.082,2.082,4.813,3.124,7.542,3.124c2.73,0,5.461-1.042,7.542-3.124L265.395,245.19c19.587-2.668,34.734-19.495,34.734-39.805
				v-18.847h141.94c38.559,0,69.931-31.372,69.931-69.935C512,78.045,480.629,46.675,442.069,46.675z M147.376,105.94h18.152
				c-0.033,0.216-0.054,0.435-0.085,0.652c-0.117,0.818-0.224,1.637-0.313,2.461c-0.038,0.356-0.07,0.715-0.103,1.073
				c-0.069,0.753-0.128,1.507-0.173,2.265c-0.02,0.33-0.042,0.658-0.058,0.99c-0.048,1.071-0.081,2.145-0.081,3.223
				c0,1.078,0.033,2.153,0.081,3.224c0.016,0.331,0.037,0.659,0.058,0.989c0.045,0.757,0.103,1.514,0.173,2.268
				c0.032,0.357,0.064,0.714,0.102,1.069c0.091,0.829,0.197,1.656,0.316,2.479c0.031,0.211,0.051,0.426,0.083,0.637h-18.152V105.94z
				 M121.259,205.055c-22.22-22.22-16.222-44.892-12.611-53.607c3.79-9.151,10.379-16.802,17.393-20.915v7.403
				c0,5.891,4.776,10.667,10.668,10.667h35.788c9.436,18.252,26.666,31.84,47.267,36.324v15.383l-66.655,66.655
				C151.258,249.734,143.633,227.428,121.259,205.055z M40.838,439.572l-15.085-15.084l199.268-199.265
				c3.581,6.281,8.804,11.503,15.084,15.086L40.838,439.572z M278.795,205.385L278.795,205.385c0,10.393-8.455,18.849-18.846,18.849
				h-0.004c-10.391,0-18.846-8.456-18.846-18.849v-18.847h37.697V205.385z M442.069,165.203H289.462h-54.816
				c-20.567,0-38.179-12.851-45.261-30.94c-0.025-0.067-0.043-0.135-0.068-0.202c-0.537-1.381-1.007-2.781-1.412-4.198
				c-0.009-0.026-0.015-0.052-0.022-0.078c-0.18-0.635-0.344-1.273-0.498-1.913c-0.039-0.164-0.077-0.33-0.114-0.494
				c-0.123-0.534-0.236-1.07-0.339-1.608c-0.053-0.278-0.102-0.559-0.15-0.84c-0.079-0.45-0.155-0.901-0.221-1.354
				c-0.055-0.378-0.1-0.76-0.146-1.139c-0.046-0.378-0.094-0.753-0.131-1.132c-0.046-0.482-0.078-0.969-0.111-1.455
				c-0.02-0.292-0.046-0.583-0.061-0.876c-0.038-0.786-0.061-1.578-0.061-2.374c0-0.796,0.021-1.586,0.061-2.372
				c0.015-0.293,0.041-0.586,0.061-0.878c0.032-0.485,0.065-0.971,0.111-1.452c0.037-0.38,0.085-0.757,0.131-1.135
				c0.046-0.379,0.091-0.758,0.146-1.134c0.066-0.456,0.142-0.908,0.221-1.359c0.049-0.278,0.096-0.556,0.15-0.833
				c0.104-0.54,0.218-1.077,0.34-1.613c0.036-0.162,0.074-0.325,0.112-0.486c0.155-0.643,0.319-1.283,0.5-1.92
				c0.007-0.025,0.014-0.047,0.019-0.07c0.406-1.417,0.877-2.818,1.414-4.201c0.026-0.066,0.044-0.134,0.067-0.201
				c7.082-18.088,24.693-30.938,45.262-30.938h207.423c26.796,0,48.596,21.799,48.596,48.594
				C490.665,143.401,468.865,165.203,442.069,165.203z" />
                                            <path d="M313.972,89.339h-79.326c-5.891,0-10.668,4.777-10.668,10.667c0,5.891,4.777,10.667,10.668,10.667h79.326
				c5.892,0,10.667-4.777,10.667-10.667C324.639,94.116,319.865,89.339,313.972,89.339z" />
                                            <path d="M344.87,89.343h-0.256c-5.892,0-10.667,4.777-10.667,10.667c0,5.891,4.776,10.667,10.667,10.667h0.256
				c5.892,0,10.667-4.777,10.667-10.667C355.538,94.12,350.762,89.343,344.87,89.343z" />
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="line">
                            <div class="inner-line" percentage='<?php echo Helper::getPercentage($o_report, $total_report); ?>%'></div>
                            <span><?php echo Helper::getPercentage($o_report, $total_report); ?>%</span>
                        </div>
                    </div>
                    <div class="card-item">
                        <div class="card-body">
                            <div class="card-info">
                                <strong><?php echo number_format($followers); ?></strong>
                                <p>Total followers</p>
                            </div>
                            <svg fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve">
                                <g>
                                    <g>
                                        <rect x="6" y="23" width="2" height="33" />
                                        <rect x="18" y="23" width="2" height="33" />
                                        <path d="M12,13h2c2.8,0,5-2.2,5-5V5c0-2.8-2.2-5-5-5h-2C9.2,0,7,2.2,7,5v3C7,10.8,9.2,13,12,13z M9,5c0-1.7,1.3-3,3-3h2
			c1.7,0,3,1.3,3,3v3c0,1.7-1.3,3-3,3h-2c-1.7,0-3-1.3-3-3V5z" />
                                        <polygon points="10,38 12,38 12,56 14,56 14,38 16,38 16,36 10,36 		" />
                                        <path d="M23,36v20h2V36V23v-1c0-3.9-3.1-7-7-7H8c-3.9,0-7,3.1-7,7v14h2V22c0-2.8,2.2-5,5-5h10c2.8,0,5,2.2,5,5v1V36z" />
                                        <rect x="35" y="23" width="2" height="33" />
                                        <path d="M29,13h2c2.8,0,5-2.2,5-5V5c0-2.8-2.2-5-5-5h-2c-2.8,0-5,2.2-5,5v3C24,10.8,26.2,13,29,13z M26,5c0-1.7,1.3-3,3-3h2
			c1.7,0,3,1.3,3,3v3c0,1.7-1.3,3-3,3h-2c-1.7,0-3-1.3-3-3V5z" />
                                        <polygon points="33,38 33,36 27,36 27,38 29,38 29,56 31,56 31,38 		" />
                                        <path d="M52,15H42v2h10c2.8,0,5,2.2,5,5v14h2V22C59,18.1,55.9,15,52,15z" />
                                        <path d="M40,36v20h2V36V23v-1c0-3.9-3.1-7-7-7H25v2h10c2.8,0,5,2.2,5,5v1V36z" />
                                        <rect x="52" y="23" width="2" height="33" />
                                        <path d="M46,13h2c2.8,0,5-2.2,5-5V5c0-2.8-2.2-5-5-5h-2c-2.8,0-5,2.2-5,5v3C41,10.8,43.2,13,46,13z M43,5c0-1.7,1.3-3,3-3h2
			c1.7,0,3,1.3,3,3v3c0,1.7-1.3,3-3,3h-2c-1.7,0-3-1.3-3-3V5z" />
                                        <polygon points="50,38 50,36 44,36 44,38 46,38 46,56 48,56 48,38 		" />
                                        <rect x="1" y="58" width="2" height="2" />
                                        <rect x="5" y="58" width="2" height="2" />
                                        <rect x="9" y="58" width="2" height="2" />
                                        <rect x="13" y="58" width="2" height="2" />
                                        <rect x="17" y="58" width="2" height="2" />
                                        <rect x="21" y="58" width="2" height="2" />
                                        <rect x="25" y="58" width="2" height="2" />
                                        <rect x="29" y="58" width="2" height="2" />
                                        <rect x="33" y="58" width="2" height="2" />
                                        <rect x="37" y="58" width="2" height="2" />
                                        <rect x="41" y="58" width="2" height="2" />
                                        <rect x="45" y="58" width="2" height="2" />
                                        <rect x="49" y="58" width="2" height="2" />
                                        <rect x="53" y="58" width="2" height="2" />
                                        <rect x="57" y="58" width="2" height="2" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="line">
                            <div class="inner-line" percentage='<?php echo Helper::getPercentage($o_followers, $followers); ?>%'></div>
                            <span><?php echo Helper::getPercentage($o_followers, $followers); ?>%</span>
                        </div>
                    </div>

                    <div class="card-item">
                        <div class="card-body">
                            <div class="card-info">
                                <strong><?php echo number_format($comments); ?></strong>
                                <p>Total comments</p>
                            </div>
                            <svg viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                    <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-152.000000, -255.000000)" fill="#000000">
                                        <path d="M168,281 C166.832,281 165.704,280.864 164.62,280.633 L159.912,283.463 L159.975,278.824 C156.366,276.654 154,273.066 154,269 C154,262.373 160.268,257 168,257 C175.732,257 182,262.373 182,269 C182,275.628 175.732,281 168,281 L168,281 Z M168,255 C159.164,255 152,261.269 152,269 C152,273.419 154.345,277.354 158,279.919 L158,287 L165.009,282.747 C165.979,282.907 166.977,283 168,283 C176.836,283 184,276.732 184,269 C184,261.269 176.836,255 168,255 L168,255 Z M175,266 L161,266 C160.448,266 160,266.448 160,267 C160,267.553 160.448,268 161,268 L175,268 C175.552,268 176,267.553 176,267 C176,266.448 175.552,266 175,266 L175,266 Z M173,272 L163,272 C162.448,272 162,272.447 162,273 C162,273.553 162.448,274 163,274 L173,274 C173.552,274 174,273.553 174,273 C174,272.447 173.552,272 173,272 L173,272 Z" id="comment-2" sketch:type="MSShapeGroup">

                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="line">
                            <div class="inner-line" percentage='<?php echo Helper::getPercentage($o_comments, $comments); ?>%'></div>
                            <span><?php echo Helper::getPercentage($o_comments, $comments); ?>%</span>
                        </div>
                    </div>

                </div>
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


    document.addEventListener("DOMContentLoaded", () => {
        const innerLines = document.querySelectorAll('.inner-line');

        innerLines.forEach(innerLine => {
            const percentage = innerLine.getAttribute('percentage');
            if (percentage) {
                innerLine.style.setProperty('--percentage-width', percentage);
            }
        });
    });
</script>

</html>