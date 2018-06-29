<?php

namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use core\Session;

use core\Mail;

class ContactController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        if(!Session::exists('token')) Session::put('token', bin2hex(random_bytes(32)));

        $token = Session::get('token');

        $this->view->render($response, 'route.view.contact.html.twig', ['token' => $token]);
    }

    public function contactMessageSent(Request $request, Response $response)
    {
        $this->view->render($response, 'route.view.contact.sent.html.twig');
    }

    public function postContactMessage(Request $request, Response $response)
    {
        $formData = $request->getParsedBody();

        $email      = $formData['email'];
        $message    = $formData['message'];
        $name       = $formData['name'];
        // TODO: store contact recipients
        $recipient  = $formData['recipient'];
        $token      = $formData['token'];

        if(!hash_equals(Session::get('token'), $token)) die();

        $errors = [];

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email format';

        if(strlen($name) === 0)    $errors['name']    = 'This field is required';
        if(strlen($name) < 3)      $errors['name']    = 'Name must be minimum 3 characters long';
        if(strlen($message) === 0) $errors['message'] = 'This field is required';
        if(strlen($message) < 30)  $errors['message'] = 'Name must be minimum 30 characters long';

        if(count($errors))
        {
            return $response->withStatus(401, 'Could not prepare your message.')->withJson(['error' => $errors]);
        }
        try {
            error_reporting(0);
            $mail = new Mail();
            $mail->setFrom($email, html_entity_decode ($name));
            $mail->Subject = getConfig('organisation.name') . ' - new contact form message from ' . $email;
            // TODO: change this to real email
            $mail->addAddress($recipient, 'Recipient Name');
            $mail->Body = html_entity_decode ($message);
            $mail->send();

        }
        catch(\Exception $e)
        {
            $errors['email'] = 'Failed to send your message. Please try again later.';
        }

        return $response->withStatus(200, 'Your message has been sent successfully')
            ->withJson(['message' => 'Your message has been sent successfully']);
    }
}