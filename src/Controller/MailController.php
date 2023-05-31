<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Agent;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;






class MailController extends AbstractController
{
        /**
         * @Route("/mail", name="app_mail")
         */
    public function index(): Response    {
       
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }

     /**
     * @Route("/send-email", name="send_email", methods={"POST"})
     */
    public function sendEmail(Request $request, MailerInterface $mailer):JsonResponse
    {
        // Parse input data from the request
       
        $jsonData = $request->getContent();

        $data = json_decode($jsonData, true);
        
        // Create a new email message
        $email = new Email();
        $email->from('mohamedamineraboudi@gmail.com')
              ->to($data['recipient'])
              ->subject($data['subject'])
              ->html($data['message']);

        // Send the email using Swift Mailer
        $mailer->send($email);

        // Return a success response to the Angular front-end
        return new JsonResponse(['message' => 'Email sent successfully']);
    }
    /**
     * @Route("/send-ag", name="send_ag", methods={"POST"})
     */
    public function sendAg(Request $request, MailerInterface $mailer)
    {
        // Parse input data from the request
        
        $data = json_decode($request->getContent(), true);

        // Create a new email message
        $email = new Email();
        $email->from('mohamedamineraboudi@gmail.com')
              ->to($data['recipient'])
              ->subject($data['subject'])
              ->html($data['message']);

        // Send the email using Swift Mailer
        $mailer->send($email);

        // Return a success response to the Angular front-end
        return new JsonResponse(['message' => 'Email sent successfully']);
    }

     /**
     * @Route("/deny-ag", name="deny_ag", methods={"POST"})
     */
    public function denyAg(Request $request,MailerInterface $mailer):JsonResponse
    {
        // Parse input data from the request
        $data = json_decode($request->getContent(), true);
        
       
        $email = new Email();
        $email->from('mohamedamineraboudi@gmail.com')
              ->to($data['recipient'])
              ->subject($data['subject'])
              ->html($data['message']);

        // Send the email using Swift Mailer
        $mailer->send($email);

        // Return a success response to the Angular front-end
        return new JsonResponse(['message' => 'Email sent successfully']);

    }

   
}
