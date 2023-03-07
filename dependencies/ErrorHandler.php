<?php

use Psr\Container\ContainerInterface;

return [
    'error' => function (ContainerInterface $container) {
        $errorHandler = function ($err, &$response, $msgStatus = 500) {
            $response = $response->withStatus($msgStatus);
            return [
                'exception' => [
                    'message' => $err->getMessage()
                ]
            ];
        };

        return $errorHandler;
    },

];
