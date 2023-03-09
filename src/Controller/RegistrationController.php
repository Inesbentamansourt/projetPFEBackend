<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Membre;
use App\Form\MembreType;
use Nelmio\ApiDocBundle\Annotation\Security;


use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;
use App\Service\UserService;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register1(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

       /**
     *@OA\RequestBody( required=true, @OA\JsonContent (  
     *               @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="test@gmail.com"
     *               ),    
     *               @OA\Property(
     *                   property="password",
     *                   type="string",
     *                   example="12345678"
     *               ),
     *               @OA\Property(
     *                   property="cin",
     *                   type="string",
     *                   example="13548692"
     *               )
    *),)
     * @OA\Response (
     *     response=201,
     *     description="Success",
     * )
     *  @OA\Response(
     *     response="300",
     *       description="FORMTYPE IS INVALID",
     * )
     * @OA\Response(
     *     response="400",
     *     description="BAD REQUEST",
     * )
     * @OA\Tag(name="Authentication")
     * @Route("/add/register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $userForm = $this->createForm(RegistrationFormType::class, $user, array('csrf_protection' => false));
        $userForm->submit($data);        
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $data['password']
                )
            );
            
        //$user = $this->UserService->persist($user);
        $entityManager->persist($user);
        $entityManager->flush();
        $request->getSession()->getFlashBag()->add('success', 'Ajout avec succée !');
        return new JsonResponse($data);
    }
}
