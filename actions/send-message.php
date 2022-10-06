<?php

require 'vendor/autoload.php';
require './config/login.config.php';
require './config/message.config.php';

use HeadlessChromium\BrowserFactory;

$browserFactory = new BrowserFactory();
$browser = $browserFactory->createBrowser([
    "headless" => false,
    'enableImages' => false,
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

    // ENVIAR A MENSAGEM PRIVADA PARA O USUÁRIO
    $messagesNo = 3;
    for ($i = 1; $i <= $messagesNo; $i++) {
        $page->navigate("https://atelier801.com/new-dialog")->waitForNavigation();
        $page->evaluate("
            let dest = document.getElementById('destinataire');
            let sub  = document.getElementById('objet');
            let msg  = document.getElementById('message_conversation');
            
            dest.value = '$MESSAGE_DESTINARY';
            sub.value  = '$MESSAGE_SUBJECT';
            msg.value  = '$MESSAGE_CONTENT';
            
            let sendMessage = document.getElementsByClassName('btn-post')[0];
            sendMessage.click();
        ")->waitForPageReload();

        echo "Mensagem enviada para o usuário $MESSAGE_DESTINARY. ($i/$messagesNo) \n";
    }

} catch(Exception $e) {
    echo $e->getMessage();
    $browser->close();
}