<?php

$RANDOM_CODE = "801" . random_int(100, 999);

// DADOS PARA O ENVIO DE MENSAGEM (send-message.php)

$MESSAGE_DESTINARY  = "Neneuh#0000"; // USUÁRIO DE DESTINO
$MESSAGE_SUBJECT    = "[MENSAGEM AUTOMÁTICA] $RANDOM_CODE"; // ASSUNTO DA MENSAGEM
$MESSAGE_CONTENT    = "Código de verificação: [b]$RANDOM_CODE\[/b]"; // CONTEÚDO DA MENSAGEM