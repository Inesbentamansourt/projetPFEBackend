<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use App\Service\UserService;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Project;


class UserController extends AbstractController
{
    /**
     * @var UserService
     */

    public function __construct(UserService $UserService , UserRepository $UserRepository)
    {
        $this->UserService = $UserService;
        $this->UserRepository = $UserRepository;
    }

    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="username",
     *                   type="string",
     *                   example="ines.bt2001@gmail.com"
     *               ),     
     *               @OA\Property(
     *                   property="password",
     *                   type="string",
     *                   example="17/08/2001?iNe"
     *               ),
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
     *       description="BAD REQUEST",
     * )
     * @OA\Response(
     *   response="401",
     *       description="Unauthorized",
     * )
     * @Security(name="Bearer") 
     * @OA\Tag(name="User")
     * @Route("/api/login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);
       
        return new JsonResponse($data);

    }


 /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="karim@gmail.com"
     *               ),     
     *               @OA\Property(
     *                   property="password",
     *                   type="string",
     *                   example="karim123456"
     *               ),
     *               @OA\Property(
     *                   property="cin",
     *                   type="string",
     *                   example="123456789"
     *               ),
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
     *       description="BAD REQUEST",
     * )
     * @OA\Response(
     *   response="401",
     *       description="Unauthorized",
     * )
     * @Security(name="Bearer") 
     * @OA\Tag(name="User")
     * @Route("/api/update/User/{id}", methods={"PUT"})
     */
    public function updateUser(Request $request, User $User)
    {
        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(UserType::class, $User, array('csrf_protection' => false));
        $form->submit($data, false);

        $User = $this->UserService->persist($User);
        return new JsonResponse($this->UserService->toJson($User));
    }

    /**
     * @OA\Tag(name="User")
     * @Security(name="Bearer") 
     * @Route("/api/delete/User/{id}", methods={"DELETE"})
     */
    public function deleteUser($id, Request $request)
    {
        $User = $this->UserService->get($id);
        try {
            $this->UserService->remove($User);
            $request->getSession()->getFlashBag()->add('success', 'User supprimé avec succès !');
            return new JsonResponse('done');
        } catch (\Exception $exception) {
             $request->getSession()->getFlashBag()->add('danger', 'un ou plusieurs produit liés  à cette entité !');
             return new JsonResponse($exception->getMessage());
        }
       
    }
    //  /**
    //  * @OA\Tag(name="User")
    //  * @Security(name="Bearer") 
    //  * @Route("/api/login", methods={"GET"})
    //  */
    // public function liste()
    // {
    //     $liste = $this->UserService->getUser();

    //     $jsonUser = [];
    //     foreach ($liste as $key => $User){
    //         $jsonUser[$key] = $this->UserService->toJson($User);
    //     }

    //     return new JsonResponse($jsonUser);
    // }

}




