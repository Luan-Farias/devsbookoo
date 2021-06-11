<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/PostDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'photos';

$id = filter_input(INPUT_GET, 'id');
if (!$id || empty($id))
{
    $id = $userInfo->getId();
}

$postDao = new PostDaoPostgres($pdo);
$userDao = new UserDaoPostgres($pdo);

$user = $userDao->findById($id, true);

if (!$user)
{
    header('Location: ' . $base);
    exit;
}

if ($user->getId() !== $userInfo->getId()) {
    $activeMenu = '';
}

require './partials/header.php';
require './partials/aside.php';

?>
<section class="feed">
    <?php
    require './partials/profile-header.php';
    ?>

    <div class="row">
        <div class="column">
            <div class="box">
                <div class="box-body">
                    <div class="full-user-photos">
                        <?php foreach($user->getPhotos() as $photo): ?>
                        <div class="user-photo-item">
                            <a href="#modal-<?= $photo->getId(); ?>" rel="modal:open">
                                <img src="<?= $base; ?>/media/uploads/<?= $photo->getBody(); ?>" />
                            </a>
                            <div id="modal-<?= $photo->getId(); ?>" style="display:none">
                                <img src="<?= $base; ?>/media/uploads/<?= $photo->getBody(); ?>" />
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (count($user->getPhotos()) === 0): ?>
                        Não há Fotos
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require './partials/footer.php';
?>
