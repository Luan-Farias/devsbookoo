<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/PostDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'friends';

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

$isFollowing = false;
if ($user->getId() !== $userInfo->getId()) {
    $activeMenu = '';
    $isFollowing = $userRelationDao->isFollowing($userInfo->getId(), $user->getId());
}

require './partials/header.php';
require './partials/aside.php';
?>
<section class="feed">
    <?php require './partials/profile-header.php'; ?>

    <div class="row">
        <div class="column">
            <div class="box">
                <div class="box-body">
                    <div class="tabs">
                        <div class="tab-item" data-for="followers">
                            Seguidores
                        </div>
                        <div class="tab-item active" data-for="following">
                            Seguindo
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-body" data-item="followers">
                            <div class="full-friend-list">
                                <?php foreach($user->getFollowers() as $follower): ?>
                                <div class="friend-icon">
                                    <a href="<?= $base; ?>/perfil.php?id=<?= $follower->getId(); ?>">
                                        <div class="friend-icon-avatar">
                                            <img src="<?= $base; ?>/media/avatars/<?= $follower->getAvatar(); ?>" />
                                        </div>
                                        <div class="friend-icon-name">
                                            <?= $follower->getName(); ?>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="tab-body" data-item="following">
                            <div class="full-friend-list">
                                <?php foreach($user->getFollowing() as $following): ?>
                                <div class="friend-icon">
                                    <a href="<?= $base; ?>/perfil.php?id=<?= $following->getId(); ?>">
                                        <div class="friend-icon-avatar">
                                            <img src="<?= $base; ?>/media/avatars/<?= $following->getAvatar(); ?>" />
                                        </div>
                                        <div class="friend-icon-name">
                                            <?= $following->getName(); ?>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require './partials/footer.php'; ?>
