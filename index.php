<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/PostDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'home';

$postDao = new PostDaoPostgres($pdo);
$feed = $postDao->getHomeFeed($userInfo->getId());

require './partials/header.php';
require './partials/aside.php';
?>
<section class="feed mt-10">
    <div class="row">
        <div class="column pr-5">
            <?php require './partials/feed-editor.php'; ?>

            <?php foreach($feed as $feedItem): ?>
                <?php require './partials/feed-item.php'; ?>
            <?php endforeach; ?>
        </div>
        <?php require './partials/banners.php'; ?>
    </div>
</section>
<?php require './partials/footer.php'; ?>
