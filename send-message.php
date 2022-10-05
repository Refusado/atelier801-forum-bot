<?php

require 'vendor/autoload.php';
require 'login.config.php';
require 'message.config.php';

use HeadlessChromium\BrowserFactory;

$browserFactory = new BrowserFactory();
$browser = $browserFactory->createBrowser([
    "headless" => false
]);

try {
    $page = $browser->createPage();
    $page->navigate('https://atelier801.com/login')->waitForNavigation();
    
    $page->evaluate("
        let user = document.getElementById('auth_login_1');
        let pass = document.getElementById('auth_pass_1');
        let loginBtn = document.querySelector('button[type=submit]');

        user.value = '$FORUM_USER';
        pass.value = '$FORUM_PASS';

        loginBtn.click();
    ")->waitForPageReload();

    $page->navigate("https://atelier801.com/new-dialog")->waitForNavigation();
    
    $page->evaluate("
        let dest = document.getElementById('destinataire');
        let sub  = document.getElementById('objet');
        let msg  = document.getElementById('message_conversation');
        let sendMessage = document.getElementsByClassName('btn-post')[0];

        dest.value = '$MESSAGE_DESTINARY';
        sub.value  = '$MESSAGE_SUBJECT';
        msg.value  = '$MESSAGE';

        sendMessage.click();
    ")->waitForPageReload();

} catch(Exception $e) {
    echo $e->getMessage();
    $browser->close();
}