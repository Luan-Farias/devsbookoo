<?php
/**
 * @var String $base
 * @var User $userInfo
 */
$userFirstName = current(explode(' ', $userInfo->getName()));
?>

<div class="box feed-new">
    <div class="box-body">
        <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?= $base; ?>/media/avatars/<?= $userInfo->getAvatar(); ?>" />
            </div>
            <div class="feed-new-input-placeholder">
                O que você está pensando, <?= $userFirstName; ?>?
            </div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="feed-new-photo">
                <img src="<?= $base; ?>/assets/images/photo.png" />
                <input type="file" name="photo" class="feed-new-file" accept="image/png, image/jpeg, image/jpg" hidden />
            </div>
            <div class="feed-new-send">
                <img src="<?= $base; ?>/assets/images/send.png" />
            </div>
            <form class="feed-new-form" action="<?= $base; ?>/feed_editor_action.php" method="post">
                <input type="hidden" name="body" />
            </form>
        </div>
    </div>
</div>

<script>
const feedInput = document.querySelector('.feed-new-input');
const feedSubmit = document.querySelector('.feed-new-send');
const feedForm = document.querySelector('.feed-new-form');
const feedPhoto = document.querySelector('.feed-new-photo');
const feedFile = document.querySelector('.feed-new-file');

feedPhoto.addEventListener('click', function(){
    feedFile.click();
});

feedFile.addEventListener('change', async function(){
    const photo = feedFile.files[0];
    const formData = new FormData();

    formData.append('photo', photo);
    const req = await fetch('ajax_upload.php', {
        method: 'POST',
        body: formData
    });
    const json = await req.json();

    if(json.error != '') {
        alert(json.error);
    }

    window.location.href = window.location.href;
});

feedSubmit.addEventListener('click', () => {
    const value = feedInput.innerText.trim();
    feedForm.querySelector('input[name=body]').value = value;

    feedForm.submit();
});
</script>
