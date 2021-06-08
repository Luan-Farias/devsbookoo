<?php

require './config.php';
require './models/Auth.php';
require './dao/PostDaoPostgres.php';

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

        <div class="column side pl-5">
            <div class="box banners">
                    <div class="box-header">
                        <div class="box-header-text">Patrocínios</div>
                        <div class="box-header-buttons"></div>
                    </div>
                    <div class="box-body">
                        <a href=""
                            ><img
                                src="https://alunos.b7web.com.br/media/courses/php.jpg"
                        /></a>
                        <a href=""
                            ><img
                                src="https://alunos.b7web.com.br/media/courses/laravel.jpg"
                        /></a>
                    </div>
                </div>
                <div class="box">
                    <div class="box-body m-10">Criado com ❤️ por B7Web</div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require './partials/footer.php';
?>
