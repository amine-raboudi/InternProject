<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\User;



class LoginController extends AbstractController
{
       /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function loginAPI(Request $request, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $jwtManager): Response
    {
        $param=json_decode($request->getContent(),true );
        // Retrieve the user credentials from the request
        $email =$param['email'];
        $password =$param['password'];
        $user=$this->getDoctrine()->getRepository(User::class)->findOneByMail($email);
        // Validate the user credentials (you can replace this with your own logic)7
        
        if ($password==$user->getPassword() &&  $email==$user->getEmail()) {
            $token = $jwtManager->create($user);

            return $this->json(['token' => $token,'role' => $user->getRoles()]);
            
        }

        return $this->json(['error' => 'Invalid credentials'], 401);
        
        // Return an error response if the credentials are invalid
    }

}
