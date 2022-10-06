<?php

require 'vendor/autoload.php';
require './config/login.config.php';

use HeadlessChromium\BrowserFactory;

$browserFactory = new BrowserFactory();
$browser = $browserFactory->createBrowser([
    "headless" => false
]);

try {
    $page = $browser->createPage();

    // REALIZA O LOGIN NO FÓRUM
    $page->navigate('https://atelier801.com/login')->waitForNavigation();
    $page->evaluate("
        let user = document.getElementById('auth_login_1');
        let pass = document.getElementById('auth_pass_1');
        
        user.value = '$FORUM_USER';
        pass.value = '$FORUM_PASS';
        
        let loginBtn = document.querySelector('button[type=submit]');
        loginBtn.click();
    ")->waitForPageReload();

    echo "Login realizado: $FORUM_USER \n";

    // LIMPA A LISTA DE IGNORADOS
    $page->navigate("https://atelier801.com/blacklist?pr=$I_NICK%23$I_TAG")->waitForNavigation();
    $playersNo = $page->evaluate("
        document.getElementsByClassName('cadre-ignore-nom').length
    ")->getReturnValue();

    echo "Há $playersNo jogadores na lista de ignorados: $FORUM_USER \n";

    for ($i = 1; $i <= $playersNo; $i++) {
        $currentPlayerNick = $page->evaluate("
            document.getElementsByClassName('cadre-ignore-nom')[0].textContent
        ")->getReturnValue();

        $page->evaluate("
            const deleteBtn = document.getElementsByClassName('element-menu-contextuel')[0]
            deleteBtn.click();
        ")->waitForPageReload();

        echo "$currentPlayerNick removido da lista de ignorados. ($i/$playersNo) \n";
    }
} catch(Exception $e) {
    echo $e->getMessage();
    $browser->close();
}