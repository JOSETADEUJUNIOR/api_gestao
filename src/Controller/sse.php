<?php

namespace Src\Controller;

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Defina um tempo limite de execução para evitar que a conexão seja fechada
set_time_limit(0);

// Verifique periodicamente se há novas ordens de serviço
while (true) {
    // Lógica para obter as novas ordens de serviço
    $novaOrdem = obterNovaOrdem(); // Substitua esta função com sua lógica real

    // Se houver uma nova ordem, envie um evento SSE para o cliente
    if (!empty($novaOrdem)) {
        echo "event: nova_ordem\n";
        echo "data: " . json_encode($novaOrdem) . "\n\n";
        flush();
    }

    // Aguarde um período antes de verificar novamente
    sleep(5); // Defina o tempo desejado entre as verificações em segundos
}
?>
