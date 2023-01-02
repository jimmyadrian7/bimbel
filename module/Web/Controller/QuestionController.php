<?php
namespace Bimbel\Web\Controller;

use \Bimbel\Master\Controller\Controller;

class QuestionController extends Controller
{
    public function submitQuestion($request)
    {
        $result = "OK";

        try 
        {
            $dataPost = $request->getParsedBody();
            $data = [
                "name" => $dataPost['name'],
                "email" => $dataPost['email'],
                "subject" => $dataPost['subject'],
                "message" => $dataPost['message']
            ];
            $mailer = $this->container->get('mailer');
            $mailer->sendMessage('Web/View/mail.twig', $data, function($message) use($data) {
                $message->setTo("jimmyadrian27799@gmail.com", "Jimmy");
                $message->setSubject($data["subject"]);
            });
        }
        catch(\Error $e) 
        {
            throw new \Exception($e->getMessage());
        }

        return $result;
    }
}
