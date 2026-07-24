<?php

// Perguntas frequentes da central de ajuda do Nexo Links (renderizadas pelo
// HelpController). As respostas podem conter HTML: são impressas com {!! !!}.
return [
    'faqs' => [
        [
            'q' => 'Como crio a minha página?',
            'a' => 'Clique em "Crie sua página", escolha um nome de usuário — ele vira sua URL pública em /seu-usuario — e confirme seu e-mail com o link que enviamos. Sua página fica no ar na hora.',
        ],
        [
            'q' => 'Como adiciono e reordeno meus links?',
            'a' => 'No painel, toque em "+ Adicionar link" e dê a ele um título e uma URL. Arraste a alça à esquerda de cada cartão para reordenar, e use Ocultar para manter um link sem exibi-lo ou Editar para alterá-lo quando quiser.',
        ],
        [
            'q' => 'Posso agendar links, destacá-los ou adicionar uma contagem regressiva?',
            'a' => 'Sim. Ao criar ou editar um link você pode definir datas de início e fim opcionais para que ele se publique e despublique sozinho, marcar "Destacar este link" para o tratamento com a cor de destaque, ou ativar uma contagem regressiva para que os visitantes vejam um cronômetro até ele começar.',
        ],
        [
            'q' => 'Um link pode enviar um e-mail, me ligar ou abrir o WhatsApp?',
            'a' => 'Os links aceitam https://, mailto: e tel:, então um botão pode enviar um e-mail ou ligar para você. Use "Montar um link de WhatsApp" para gerar um link wa.me com uma mensagem já preenchida.',
        ],
        [
            'q' => 'Como funcionam os ícones sociais?',
            'a' => 'Em "Ícones sociais", escolha uma plataforma e informe seu usuário, e-mail ou telefone. Eles aparecem como ícones no rodapé da sua página. Prefere um botão grande? Adicione-o como um link normal.',
        ],
        [
            'q' => 'Como personalizo o visual da minha página?',
            'a' => 'Em Design, envie um avatar e um banner, escreva sua bio e escolha uma paleta de destaque e um fundo: padrão, cor sólida ou gradiente. A cor do texto se adapta automaticamente para que sua página continue legível.',
        ],
        [
            'q' => 'O que as estatísticas mostram e elas rastreiam os visitantes?',
            'a' => 'As estatísticas mostram cliques totais, visitantes únicos, cliques por dia e seus principais referenciadores. Os números são coletados sem cookies e sem armazenar dados pessoais — os visitantes nunca são rastreados de um dia para o outro.',
        ],
        [
            'q' => 'Como compartilho minha página?',
            'a' => 'Copie sua URL em "Compartilhe sua página" no painel, ou baixe seu código QR em SVG e imprima onde quiser — ele sempre aponta para a sua página.',
        ],
    ],
];
