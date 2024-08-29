<?php
$path = "/archiveiot";
include '../vendor/autoload.php';

use app\controller\CommentController;
use app\controller\EngageController;
use app\controller\ThreatController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$threat = ['threat_title' => 'Undefined'];
$totalComment = 0;
$totalLikes = 0;
$totalFollowers = 0;
$isLike = false;
$isFollow = false;
$engageSlug = "";

$comments = [];
session_start();
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 8) {

    $controller = new ThreatController();
    $engageController = new EngageController();
    $comment_controller = new CommentController();


    if (isset($_POST["btnPostComment"])) {
        $_id = (int) $_SESSION["user"]["user_id"];
        $threat_id = (int) $_POST["threat_id"];
        $desc = $_POST["comment"];
        $attachments = $_FILES["attachment"];

        if (strlen(trim($desc)) > 4 && $_id > 0 && $threat_id > 0) {

            $payload = array(
                '_threat_id' => $threat_id,
                '_user_id' => $_id,
                '_description' => $desc,
            );



            $response = json_decode($comment_controller->addComment($payload, $attachments), true);

            if (isset($response["message"]) && strlen(trim($response["message"])) > 4) {
                echo "<script> alert('" . $response["message"] . "'); </script>";
            }
        } else {
            echo "<script> alert('Ensure to fill the form field correctly!'); </script>";
        }
    }



    if (isset($_GET["_comment"]) && strlen(trim($_GET["_comment"])) > 0) {
        $c_id = (int)  $_GET["_comment"];

        if ($c_id > 0) {
            $comment_res = json_decode($comment_controller->deleteComment($c_id), true);
            if (isset($comment_res["message"]) && $comment_res["status_code"] == 200) {
                echo "<script> alert('" . $comment_res["message"] . "'); </script>";
            }
        }
    }

    if (isset($_GET["slug"]) && strlen(trim($_GET["slug"])) > 5) {

        $slugs = explode("..", $_GET["slug"]);
        $slug = $slugs[count($slugs) - 1];



        if (count($slugs) > 1) {
            $engage_slug = $slugs[0];


            if (strlen(trim($engage_slug)) > 6) {

                if ($slugs[2] == "l") { //Like
                    $payload = array(
                        "_slug" => $engage_slug,
                        "_like" => (int) $slugs[1]
                    );
                    $likeRes = $engageController->likeToggle($payload);
                } else if ($slugs[2] == "f") { //Follow
                    $payload = array(
                        "_slug" => $engage_slug,
                        "_follow" => (int) $slugs[1]
                    );
                    $likeRes = $engageController->followToggle($payload);
                }
            }
        }


        $res = json_decode($controller->getThreatReportBySlug($slug), true);
        if (count($res["message"]) > 4) {
            $threat = $res['message'];

            $threat_id = (int) $res['message']["threat_id"];
            $user_id = (int) $_SESSION["user"]["user_id"];
            $totalLikes = (int) $engageController->likeCount($threat_id);
            $totalFollowers = (int) $engageController->followerCount($threat_id);
            $ue_res =  json_decode($engageController->getUserEngagement($threat_id, $user_id), true);
            if (count($ue_res["message"]) == 3) {
                $isLike = $ue_res["message"]["like_status"] == 1;
                $isFollow = $ue_res["message"]["follow_status"] == 1;
                $engageSlug = $ue_res["message"]["engage_slug"];
            } else {

                //-Register engagement
                $payload = array(
                    '_threat_id' => $threat_id,
                    '_user_id' => $user_id,
                    '_like' => 0,
                    '_follow' => 0,
                );

                $res = $engageController->registerEngagement($payload);
            }

            $comment_res = json_decode($comment_controller->getThreatComments($threat_id), true);

            if (count($comment_res["message"]) > 0) {
                $comments = $comment_res["message"];
                $totalComment = count($comments);
            }


            if (isset($_GET["read"]) && strlen(trim($_GET["read"])) > 0) {

                $_payload = array(
                    '_user_id' => $user_id,
                    '_notification_id' => (int) $_GET["read"]
                );

                $res = $controller->markNotification($_payload);
            }
        }
    }
} else {
    header("location: ./login.php");
}


$type = ["malware" => "Malware", "ddos" => "DDoS", "unauthorized_access" => "Unauthorized Access"];



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $threat["threat_title"]; ?> - ArchiveIot</title>
    <link rel="stylesheet" href="../static/styles/dashboard.css">
</head>

<style>
    .hidden {
        display: none;
    }
</style>

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
                        <a href="./search-threat.php" class="active">
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

            <?php
            if (count($threat) > 10) {
            ?>
                <section id="page_title">
                    <div class="user">
                        <div class="image_holder">
                            <?php
                            $pic = $threat["user_picture"];
                            if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                            } else echo Helper::getInitialNames($threat["user_fullname"]);
                            ?>
                        </div>
                        <div class="user_info">
                            <strong><?php echo $threat["user_fullname"]; ?></strong>
                            <p><?php echo $threat["user_email"]; ?></p>
                        </div>
                    </div>
                    <ul>
                        <li> <strong>Likes: </strong><?php echo number_format($totalLikes); ?></li>
                        <li> <strong>Followers:</strong> <?php echo number_format($totalFollowers); ?></li>
                        <li> <strong>Comments:</strong> <?php echo number_format($totalComment); ?></li>
                    </ul>
                </section>
                <section id="threat_post">
                    <ul id="threat_tabs">
                        <li data-id="post" class="active">Report</li>
                        <li data-id="step">Mitigation step</li>
                        <li data-id="others">Others</li>
                    </ul>
                    <article id="post">

                        <h2><?php echo $threat["threat_title"]; ?></h2>
                        <?php
                        echo $threat["threat_desc"];
                        ?>
                    </article>
                    <article id="step" class="hidden">
                        <h2>
                            Mitigation Steps
                        </h2>
                        <?php
                        echo $threat["mitigation_steps"];
                        ?>
                    </article>

                    <article id="others" class="hidden">
                        <h2>Other Important Information</h2>
                        <ul>
                            <li>
                                <strong>Date Detected: </strong>
                                <p><?php echo $threat["date_discovered"]; ?></p>
                            </li>
                            <li>
                                <strong>Threat Category: </strong>
                                <p><?php echo $type[$threat["threat_category"]]; ?></p>
                            </li>
                            <li>
                                <strong>Severity: </strong>
                                <p><?php echo $threat["severity_level"]; ?></p>
                            </li>

                            <li>
                                <strong>Affected Devices: </strong>
                                <p><?php echo $threat["affected_devices"]; ?></p>
                            </li>
                            <li>
                                <strong>
                                    Indicators of Compromise: </strong>
                                <p><?php echo $threat["iocs"]; ?></p>
                            </li>
                            <li>
                                <strong>Attachments: </strong>
                                <p>
                                    <?php
                                    $_url = $threat["attachment"];
                                    if (strlen(trim($_url)) > 10) {
                                    ?>
                                        <a href="<?php echo $_url; ?>" download="">Download attachment</a>
                                    <?php
                                    }
                                    ?>
                                </p>
                            </li>
                        </ul>
                    </article>
                    <div class="actions">
                        <h4 id="btnShowComment">Comments</h4>
                        <div class="choices">
                            <a href="./threat.php?slug=<?php echo $engageSlug . ".." . ($isLike ? 0 : 1) . ".." . "l" . ".." . $threat["threat_slug"]; ?>" class="<?php echo $isLike ? "active" : ""; ?>" title="Like Threat">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                </svg>
                                <span>Like</span>
                            </a>
                            <?php
                            if ($_SESSION["user"]["user_id"] != $threat["reporter_id"]) {
                            ?>
                                <a class="<?php echo $isFollow ? "active" : ""; ?>" href="./threat.php?slug=<?php echo $engageSlug . ".." . ($isFollow ? 0 : 1) . ".." . "f" . ".." . $threat["threat_slug"]; ?>" title="Follow Threat">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                    <span>Follow</span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="comments" style="cursor: pointer;">
                        <?php
                        if (count($comments) > 0) {

                        ?>
                            <ul>
                                <?php
                                foreach ($comments as $key => $comment) {
                                ?>
                                    <li>
                                        <p><?php echo $comment["comment_desc"]; ?></p>
                                        <div class="comment_info">
                                            <div class="user">
                                                <div class="image_holder">
                                                    <?php
                                                    $pic = $comment["user_picture"];
                                                    if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                                        echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                                                    } else echo Helper::getInitialNames($comment["user_fullname"]);
                                                    ?>
                                                </div>
                                                <div class="user_info">
                                                    <strong><?php echo $comment["user_fullname"]; ?></strong>
                                                    <p><?php echo $comment["user_email"]; ?></p>
                                                </div>
                                            </div>
                                            <?php
                                            if ($_SESSION["user"]["user_email"] == $comment["user_email"]) {
                                            ?>
                                                <div class="likes">
                                                    <a href="<?php echo "threat.php?slug=" . $_GET["slug"] . "&_comment=" . $comment["comment_id"]; ?>" title="Delete Comment">
                                                        <svg stroke="red" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            <?php
                                            }
                                            ?>

                                        </div>
                                        <?php
                                        $file_url = $comment["comment_attachment"];
                                        if (strlen(trim($file_url)) > 10) {
                                        ?>
                                            <a href="<?php echo $comment["comment_attachment"]; ?>" download="">Download attachment</a>
                                        <?php
                                        }
                                        ?>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        <?php
                        }
                        ?>
                        <form action="" enctype="multipart/form-data" method="post">
                            <label id="post_comment" for="comment">
                                <textarea name="comment" required id="comment" rows="5" placeholder="Enter your comment now..."></textarea>
                                <div class="vitals">
                                    <input type="hidden" name="threat_id" value="<?php echo count($threat) > 0 ? $threat["threat_id"] : 0; ?>">
                                    <input type="file" name="attachment" id="attachment">
                                    <button type="submit" name="btnPostComment">Post comment</button>
                                </div>
                            </label>
                        </form>
                    </div>
                </section>
            <?php
            } else {
            ?>
                <p>Oooooops, I believe this is a broken page. <a href="./"> let's go home</a></p>
            <?php
            }
            ?>
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


    document.querySelector("#btnShowComment").addEventListener('click', function() {
        document.querySelector("#comments").classList.toggle("hidden");
    });


    const article = document.querySelectorAll("#threat_post article");
    const tab = document.querySelectorAll("#threat_tabs li");

    console.log(tab);


    for (let i = 0; i < tab.length; i++) {
        tab[i].addEventListener('click', function(e) {
            e.target.classList.add('active');
            clearAllActiveTabs(tab, i);
            for (let m = 0; m < article.length; m++) {
                const ele = article[m];
                if (e.target.getAttribute('data-id') == ele.getAttribute("id")) {
                    ele.classList.remove("hidden");
                } else {
                    ele.classList.add("hidden");
                }
            }

        });

    }


    function clearAllActiveTabs(tab, m) {
        for (let i = 0; i < tab.length; i++) {
            if (i == m) continue;
            tab[i].classList.remove('active');
        }
    }
</script>

</html>