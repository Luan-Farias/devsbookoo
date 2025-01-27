<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './dao/PostDaoPostgres.php';
require_once './dao/UserRelationDaoPostgres.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'profile';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || empty($id))
{
    $id = $userInfo->getId();
}
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
if ($page < 1)
    $page = 1;

$postDao = new PostDaoPostgres($pdo);
$userDao = new UserDaoPostgres($pdo);
$userRelationDao = new UserRelationDaoPostgres($pdo);

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

$feedInformation = $postDao->getUserFeed($user->getId(), $page);
$feed = $feedInformation['posts'];
$pagesQuantity = $feedInformation['pages'];
$currentPage = $feedInformation['currentPage'];

require './partials/header.php';
require './partials/aside.php';
?>
<section class="feed">
    <?php require './partials/profile-header.php'; ?>

    <div class="row">
        <div class="column side pr-5">
            <div class="box">
                <div class="box-body">
                    <div class="user-info-mini">
                        <img src="<?= $base; ?>/assets/images/calendar.png" />
                        <?= date('d/m/Y', strtotime($user->getBirthdate())); ?> (<?= $user->getYearsOld(); ?> anos)
                    </div>

                    <?php if (!empty($user->getCity())): ?>
                    <div class="user-info-mini">
                        <img src="<?= $base; ?>/assets/images/pin.png" />
                        <?= $user->getCity(); ?>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($user->getWork())): ?>
                    <div class="user-info-mini">
                        <img src="<?= $base; ?>/assets/images/work.png" />
                        <?= $user->getWork(); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="box">
                <div class="box-header m-10">
                    <div class="box-header-text">
                        Seguindo
                        <span>(<?= count($user->getFollowing()); ?>)</span>
                    </div>
                    <div class="box-header-buttons">
                        <a href="<?= $base; ?>/amigos.php?id=<?= $user->getId(); ?>">ver todos</a>
                    </div>
                </div>
                <div class="box-body friend-list">
                    <?php foreach($user->getFollowing() as $followingUser): ?>
                    <div class="friend-icon">
                        <a href="<?= $base; ?>/perfil.php?id=<?= $followingUser->getId(); ?>">
                            <div class="friend-icon-avatar">
                                <img
                                    src="<?= $base; ?>/media/avatars/<?= $followingUser->getAvatar(); ?>"
                                />
                            </div>
                            <div class="friend-icon-name">
                                <?= $followingUser->getName(); ?>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="column pl-5">
            <div class="box">
                <div class="box-header m-10">
                    <div class="box-header-text">
                        Fotos
                        <span>(<?= count($user->getPhotos()); ?>)</span>
                    </div>
                    <div class="box-header-buttons">
                        <a href="<?= $base; ?>/fotos.php?id=<?= $user->getId(); ?>">ver todos</a>
                    </div>
                </div>
                <div class="box-body row m-20">
                    <?php foreach ($user->getPhotos() as $key => $photo): ?>
                    <?php if ($key === 4) break; ?>
                    <div class="user-photo-item">
                        <a href="#modal-<?= $photo->getId(); ?>" data-modal-open>
                            <img src="<?= $base; ?>/media/uploads/<?= $photo->getBody(); ?>" />
                        </a>
                        <div id="modal-<?= $photo->getId(); ?>" style="display: none">
                            <img src="<?= $base; ?>/media/uploads/<?= $photo->getBody(); ?>" />
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if($user->getId() === $userInfo->getId()): ?>
            <?php require './partials/feed-editor.php'; ?>
            <?php endif; ?>

            <?php foreach($feed as $feedItem): ?>
            <?php require './partials/feed-item.php'; ?>
            <?php endforeach; ?>
            <div class="feed-pagination">
                <?php for ($i = 0; $i < $pagesQuantity; $i++): ?>
                    <a 
                        class="<?= $i + 1 === $currentPage ? 'active' : ''; ?>"
                        href="<?= $base; ?>/perfil.php?page=<?= $i + 1; ?>&id=<?= $user->getId(); ?>">
                        <?= $i + 1; ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php if(count($feed) === 0): ?>
                Não há posts desse usuário
            <?php endif; ?>
        </div>
    </div>
</section>
<script>
window.onload = function () {
    var modal = new VanillaModal.default();
};
</script>
<?php require './partials/footer.php'; ?>
