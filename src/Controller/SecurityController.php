<?php

namespace App\Controller;

use App\Document\User;
use App\Form\UserType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(['en'=> '/sign-in/', 'es' => '/iniciar-sesion/'], name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(['en' => '/logout/', 'es' => '/cerrar-sesion/'], name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(['en' =>'/register/', 'es' => '/registro/'], name: 'app_register', methods: ['GET','POST'])]
    public function register(DocumentManager $dm, UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            /** @var User */
            $user = $form->getData();
            $password = $user->getPassword();
            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $dm->persist($user);
            $dm->flush();
            $this->addFlash('success', 'Usuario creado');
        }
        return $this->render('security/register.html.twig', ['form' => $form->createView()]);
    }
}
