<?php

use Psr\Container\ContainerInterface;

return [
    'mailer' => function (ContainerInterface $container) {
        $mailconfig = require __DIR__ . '/../config/mail.php';
        $view = $container->get('twig');
        $mailer = new \Semhoun\Mailer\Mailer($view, $mailconfig);
        $mailer->setDefaultFrom('no-reply@mail.com', 'Webmaster');
        return $mailer;
    },
];