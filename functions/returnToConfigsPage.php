<?php

function returnToConfigsPage(string $baseUrl, string $flashMessage = ''):void
{
    if (!empty($flashMessage)) $_SESSION['flash'] = $flashMessage;
    header('Location: ' . $baseUrl . '/configs.php');
    exit;
}
