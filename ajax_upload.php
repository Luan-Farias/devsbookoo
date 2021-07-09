<?php

require_once './config.php';
require_once './models/Auth.php';
require_once './models/Post.php';
require_once './dao/PostDaoPostgres.php';
require_once './functions/resizeImageAndSave.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$response = [
    'error' => '',
];

if (isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name']))
{
    $newImage = $_FILES['photo'];
    $newImageName = resizeImageAndSave($newImage, './media/uploads', 800, 800, true);

    if($newImageName)
    {
        $postDao = new PostDaoPostgres($pdo);

        $post = new Post();
        $post->setIdUser($userInfo->getId());
        $post->setType('photo');
        $post->setCreatedAt(date('Y-m-d H:i:s'));
        $post->setBody($newImageName);

        $postDao->insert($post);
    }
    else
        $array['error'] = 'Tipo de arquivo não suportado';
}
else
{
    $array['error'] = 'Nenhuma imagem enviada';
}


header('Content-Type: application/json');
echo json_encode($response);
exit;
