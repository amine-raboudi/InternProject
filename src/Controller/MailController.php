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
    public function sendEmail(Request $request, MailerInterface $mailer)
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
        $user=new User();
        $jsonData = $request->getContent();

        $data = json_decode($jsonData, true);

        $ag=$this->getDoctrine()->getRepository(Agent::class)->findOneByMail($data['recipient']);
        $user->setEmail($ag->getEmail());
        $user->setRoles($ag->getRoles());
        $user->setPassword($ag->getPassword());
        $entityManager=$this->getDoctrine()->getManager();

        $entityManager->persist($user);
        $entityManager->flush();


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
     * @Route("/deny-ag/{id}", name="deny_ag", methods={"DELETE"})
     */
    public function denyAg($id)
    {
        // Parse input data from the request
        
    
        $ag=$this->getDoctrine()->getRepository(Agent::class)->find($id);
        $user=$this->getDoctrine()->getRepository(User::class)->findOneByMail($ag->getEmail());
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->remove($user);


        $entityManager->flush();


        // Create a new email message
        
        // Return a success response to the Angular front-end
        return new JsonResponse(['message' => 'denied successfully']);
    }

   
}
