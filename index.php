<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/PostDaoPostgres.php';

$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
if ($page < 1)
    $page = 1;

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'home';

$postDao = new PostDaoPostgres($pdo);
$feedInformation = $postDao->getHomeFeed($userInfo->getId(), $page);
$feed = $feedInformation['posts'];
$pagesQuantity = $feedInformation['pages'];
$currentPage = $feedInformation['currentPage'];

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
            <div class="feed-pagination">
                <?php for ($i = 0; $i < $pagesQuantity; $i++): ?>
                    <a class="<?= $i + 1 === $currentPage ? 'active' : ''; ?>" href="<?= $base; ?>/?page=<?= $i + 1; ?>"><?= $i + 1; ?></a>
                <?php endfor; ?>
            </div>
        </div>
        <?php require './partials/banners.php'; ?>
    </div>
</section>
<?php require './partials/footer.php'; ?>
