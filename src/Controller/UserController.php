<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/registro', name: 'app_registro')]
    public function registro(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $encoder
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request); //determina si el formulario fue enviado
        if ($form->isSubmitted() && $form->isValid()) {
            //si el formulario es enviado y es valido
            $user->setActivo(true);
            $user->setRoles(['ROLE_USER']);

            $user->setPassword(
                $encoder->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            try{
                $em->persist($user);
                $em->flush();
                $this->addFlash(type: 'exito', message: USER::MSG_REGISTRO_EXITOSO);
            }catch(UniqueConstraintViolationException $e){
                $this->addFlash(type: 'danger', message: "El email ya esta registrado");
            }
            
            return $this->redirectToRoute('app_registro');
        }

        return $this->render('user/index.html.twig', [
            'formulario' => $form->createView()
        ]);
    }
}
