<?php
/**
 * @var PostComment $comment
 * @var string $base
 */
?>
<div class="fic-item row m-height-10 m-width-20">
    <div class="fic-item-photo">
        <a href="<?= $base; ?>/perfil.php?id=<?= $comment->getUser()->getId(); ?>">
            <img src="<?= $base; ?>/media/avatars/<?= $comment->getUser()->getAvatar(); ?>" />
        </a>
    </div>
    <div class="fic-item-info">
        <a href="<?= $base; ?>/perfil.php?id=<?= $comment->getUser()->getId(); ?>">
            <?= $comment->getUser()->getName(); ?>
        </a>
        <?= $comment->getBody(); ?>
    </div>
</div>