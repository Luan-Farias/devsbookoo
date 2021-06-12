<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/UserDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'search';

$searchTerm = filter_input(INPUT_GET, 's');
if (empty($searchTerm)) {
    header('Location: ' . $base);
    exit;
}

$userDao = new UserDaoPostgres($pdo);
$userList = $userDao->findUsersByName($searchTerm);

require './partials/header.php';
require './partials/aside.php';

?>
<section class="feed mt-10">
    <div class="row">
        <div class="column pr-5">
            <h2>Pesquisa por: <?= $searchTerm; ?></h2>

            <div class="full-friend-list">
                <?php foreach($userList as $user): ?>
                <div class="friend-icon">
                    <a href="<?= $base; ?>/perfil.php?id=<?= $user->getId(); ?>">
                        <div class="friend-icon-avatar">
                            <img
                                src="<?= $base; ?>/media/avatars/<?= $user->getAvatar(); ?>"
                            />
                        </div>
                        <div class="friend-icon-name">
                            <?= $user->getName(); ?>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>    
            </div>
            
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
