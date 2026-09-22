<?php

use Psr\Container\ContainerInterface;

return [
    'error' => function (ContainerInterface $container) {
        $errorHandler = function ($err, &$response, $msgStatus = null) {
            if ($msgStatus === null)
            {
                // Most call sites just do
                // $this->container->get('error')($e, $response) without a
                // status, which used to always mean "500" even when the
                // thrown \Error carried a more specific code (e.g.
                // Session throws "You are currently not logged in" with
                // code 501). Fall back to that code when it looks like a
                // real HTTP status, instead of forcing every controller
                // to remember to pass it through by hand.
                $code = $err->getCode();
                $msgStatus = ($code >= 400 && $code <= 599) ? $code : 500;
            }

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
