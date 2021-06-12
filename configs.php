<?php

require_once './config.php';
require_once './models/Auth.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'config';

require './partials/header.php';
require './partials/aside.php';
?>
<section class="feed mt-10">
    <h1>Configurações</h1>

    <?php if (!empty($_SESSION['flash'])): ?>
        <?= $_SESSION['flash']; ?>
        <?php $_SESSION['flash'] = ''; ?>
    <?php endif; ?>

    <form action="<?= $base; ?>/configs_action.php" method="post" enctype="multipart/form-data" class="config-form">
        <label class="image-input">
            Novo Avatar: <br />
            <input type="file" name="avatar" accept="image/*" /><br/>
            <img 
                src="<?= $base; ?>/media/avatars/<?= $userInfo->getAvatar(); ?>"
                alt="<?= $userInfo->getName(); ?>"
                class="image-example"
            />
        </label>

        <label class="image-input">
            Novo  Capa: <br />
            <input type="file" name="cover" accept="image/*" /><br/>
            <img 
                src="<?= $base; ?>/media/covers/<?= $userInfo->getCover(); ?>"
                alt="<?= $userInfo->getName(); ?>"
                class="image-example"
            />
        </label>

        <hr />

        <label>
            Nome Completo: <br />
            <input type="text" name="name" required value="<?= $userInfo->getName(); ?>" />
        </label>

        <label>
            E-mail: <br />
            <input type="email" name="email" required value="<?= $userInfo->getEmail(); ?>" />
        </label>

        <label>
            Data de Nascimento: <br />
            <input type="text"
                name="birthdate"
                id="birthdate"
                required
                value="<?= date('d/m/Y', strtotime($userInfo->getBirthdate())); ?>"
            />
        </label>

        <label>
            Cidade: <br />
            <input type="text" name="city" value="<?= $userInfo->getCity(); ?>" />
        </label>

        <label>
            Trabalho: <br />
            <input type="text" name="work" value="<?= $userInfo->getWork(); ?>" />
        </label>

        <hr />

        <label>
            Nova Senha: <br />
            <input type="password" name="password" />
        </label>
        
        <label>
            Confirmar Nova Senha: <br />
            <input type="password" name="password_confirmation" />
        </label>

        <button class="button" type="submit">Salvar</button>
    </form>
</section>
<script src="https://unpkg.com/imask"></script>
<script>
IMask(
    document.getElementById('birthdate'),
    {
        mask: '00/00/0000'
    }
);

document.querySelectorAll('.config-form label.image-input').forEach(labelImageInput => {
    const fileInput = labelImageInput.querySelector('input[type=file]');
    const exampleImage = labelImageInput.querySelector('.image-example');

    if (!fileInput || !exampleImage) return;

    fileInput.addEventListener('change', () => {
        const acceptedMimeTypes = ['image/jpg', 'image/jpeg', 'image/png'];
        const imageFile = fileInput.files[0];
        const typeImage = imageFile.type;

        if (acceptedMimeTypes.indexOf(typeImage) === -1) {
            fileInput.files[0] = {};
            fileInput.value = null;
            return;
        }

        const previewImageUrl = URL.createObjectURL(imageFile);
        exampleImage.src = previewImageUrl;
    });
});
</script>
<?php require './partials/footer.php'; ?>
