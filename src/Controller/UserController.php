<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
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

    #[Route('/lista', name: 'user_lista')]
    public function listar(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $encoder
    ): Response {
        $users = $em->getRepository(User::class)->findBy(['activo' => true]);
        $baneados = $em->getRepository(User::class)->findBy(['activo' => false]);
        return $this->render('user/lista.html.twig', [
            'users' => $users, 'baneados'=>$baneados
        ]);
    }

    #[Route('/banear/{id}', name: 'user_banear')]
    public function banear(
        $id,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $encoder
    ): Response {
        $user = $em->getRepository(User::class)->find(id:$id);
        $user->setActivo(False);

        if (!$user) {
            throw $this->createNotFoundException(
                'No User found for id ' . $id
            );
        }

        $user->setActivo(false);
        $em->flush();
        return $this->redirectToRoute('user_lista');
    }
}
