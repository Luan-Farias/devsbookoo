<?php
/**
 * @var Post $feedItem
 * @var string $base
 * @var User $userInfo
 */

require_once 'partials/feed-item-script.php';

$actionPhrase = '';

switch($feedItem->getType())
{
    case 'text': 
        $actionPhrase = 'fez um post';
        break;
    case 'photo':
        $actionPhrase = 'postou uma foto';
        break;
}
?>
<div class="box feed-item" data-id="<?= $feedItem->getId(); ?>">
    <div class="box-body">
        <div class="feed-item-head row mt-20 m-width-20">
            <div class="feed-item-head-photo">
                <a href="<?= $base; ?>/perfil.php?id=<?= $feedItem->getUser()->getId() ?>"><img src="<?= $base; ?>/media/avatars/<?= $feedItem->getUser()->getAvatar(); ?>" /></a>
            </div>
            <div class="feed-item-head-info">
                <a href="<?= $base; ?>/perfil.php?id=<?= $feedItem->getUser()->getId() ?>"><span class="fidi-name"><?= $feedItem->getUser()->getName(); ?></span></a>
                <span class="fidi-action"><?= $actionPhrase; ?></span>
                <br />
                <span class="fidi-date"><?= date('d/m/Y', strtotime($feedItem->getCreatedAt())) ?></span>
            </div>
            <div class="feed-item-head-btn">
                <img src="<?= $base; ?>/assets/images/more.png" />
            </div>
        </div>
        <div class="feed-item-body mt-10 m-width-20">
            <?= nl2br($feedItem->getBody()); ?>
        </div>
        <div class="feed-item-buttons row mt-20 m-width-20">
            <div class="like-btn <?= $feedItem->getLiked() ? 'on' : ''; ?>"><?= $feedItem->getLikeCount(); ?></div>
            <div class="msg-btn"><?= count($feedItem->getComments()); ?></div>
        </div>
        <div class="feed-item-comments">
            <div class="fic-answer row m-height-10 m-width-20">
                <div class="fic-item-photo">
                    <a href="<?= $base; ?>/perfil.php"><img src="<?= $base; ?>/media/avatars/<?= $userInfo->getAvatar(); ?>" /></a>
                </div>
                <input
                    type="text"
                    class="fic-item-field"
                    placeholder="Escreva um comentário"
                />
            </div>
        </div>
    </div>
</div>