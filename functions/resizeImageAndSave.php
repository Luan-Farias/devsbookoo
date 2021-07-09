<?php

function resizeImageAndSave(array $imageFile, string $dirSavePath, int $width = 0, int $height = 0, bool $preserveOriginalRatio = false): string | false
{
    if (!(isset($imageFile['tmp_name']) && !empty($imageFile['tmp_name'])))
    {
        return false;
    }

    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!in_array($imageFile['type'], $allowedTypes))
    {
        return false;
    }
    
    list($originalWidth, $originalHeight) = getimagesize($imageFile['tmp_name']);
    $ratio = $originalWidth / $originalHeight;

    $imageWidth = $width > 0 ? $width : $originalWidth;
    $imageHeight = $height > 0 ? $height : $originalHeight;

    $newWidth = $imageWidth;
    $newHeight = $newWidth / $ratio;

    if ($newHeight < $imageHeight)
    {
        $newHeight = $imageHeight;
        $newWidth = $newHeight * $ratio;
    }

    $x = ($imageWidth - $newWidth) / 2;
    $y = ($imageHeight - $newHeight) / 2;

    if ($preserveOriginalRatio)
    {
        $newWidth = $width > 0 ? $width : $originalWidth;
        $newHeight = $height > 0 ? $height : $originalHeight;
        $maxRatio = $width / $height;
        $x = 0;
        $y = 0;

        if ($maxRatio > $ratio)
            $newWidth = $newHeight * $ratio;
        else
            $newHeight = $newWidth / $ratio;

        $imageWidth = $newWidth;
        $imageHeight = $newHeight;

    }

    $finalImage = imagecreatetruecolor($imageWidth, $imageHeight);

    $image = false;

    switch($imageFile['type'])
    {
        case 'image/jpeg':
        case 'image/jpg':
            $image = imagecreatefromjpeg($imageFile['tmp_name']);
            break;
        case 'image/png':
            $image = imagecreatefrompng($imageFile['tmp_name']);
            break;
    }
    
    imagecopyresampled(
        $finalImage, $image,
        $x, $y, 0, 0, 
        $newWidth, $newHeight, $originalWidth, $originalHeight
    );

    $imageFileName = md5(time() . rand(0, 9999)) . '.jpg';
    imagejpeg($finalImage, $dirSavePath . '/' . $imageFileName, 100);

    return $imageFileName;
}
