<?php
namespace Bimbel\Web\Controller;

use \Bimbel\Master\Controller\Controller;

class QuestionController extends Controller
{
    public function submitQuestion($request, &$response)
    {
        $result = "OK";

        try 
        {
            $dataPost = $request->getParsedBody();
            $config = new \Bimbel\Web\Model\KonfigurasiWeb();
            $config = $config->first();


            $data = [
                "name" => $dataPost['name'],
                "email" => $dataPost['email'],
                "subject" => $dataPost['subject'],
                "message" => $dataPost['message']
            ];

            $question = new \Bimbel\Web\Model\UserQuestion();
            $question = $question->create($data);

            $data['config'] = $config;

            $mailer = $this->container->get('mailer');
            $mailer->sendMessage('Web/View/mail.twig', $data, function($message) use($data) {
                $message->setTo($data['config']->email, "Admin");
                $message->setSubject($data["subject"]);
            });
        }
        catch(\Error $e)
        {
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }
}
