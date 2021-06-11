<?php
/**
 * @var string $base
 * @var User $user
 */
?>
<div class="row">
    <div class="box flex-1 border-top-flat">
        <div class="box-body">
            <div
                class="profile-cover"
                style="
                    background-image: url('<?= $base; ?>/media/covers/<?= $user->getCover(); ?>');
                "
            ></div>
            <div class="profile-info m-20 row">
                <div class="profile-info-avatar">
                    <img src="<?= $base; ?>/media/avatars/<?= $user->getAvatar(); ?>" />
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-name-text">
                        <?= $user->getName(); ?>
                    </div>
                    <?php if(!empty($user->getCity())): ?>
                    <div class="profile-info-location">
                        <?= $user->getCity(); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="profile-info-data row">
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n">
                            <?= count($user->getFollowers()); ?>
                        </div>
                        <div class="profile-info-item-s">
                            Seguidores
                        </div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n">
                            <?= count($user->getFollowing()); ?>
                        </div>
                        <div class="profile-info-item-s">
                            Seguindo
                        </div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n">
                            <?= count($user->getPhotos()); ?>
                        </div>
                        <div class="profile-info-item-s">
                            Fotos
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>