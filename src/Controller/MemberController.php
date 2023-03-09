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
use App\Service\MemberService;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use App\Entity\Member;



class MemberController extends AbstractController
{
    /**
     * @var MemberService
     */

    public function __construct(MemberService $MemberService , MemberRepository $MemberRepository)
    {
        $this->MemberService = $MemberService;
        $this->MemberRepository = $MemberRepository;
    }

    /**
     * @OA\Tag(name="Member")
     * @Security(name="Bearer") 
     * @Route("/api/liste/Member", methods={"GET"})
     */
    public function liste()
    {
        $liste = $this->MemberService->getMember();

        $jsonMember = [];
        foreach ($liste as $key => $Member){
            $jsonMember[$key] = $this->MemberService->toJson($Member);
        }

        return new JsonResponse($jsonMember);
    }

    /**
     * @OA\Tag(name="Member")
     * @Security(name="Bearer") 
     * @Route("/api/Member/{id}", methods={"GET"})
     */
    public function getMember(Member $Member)
    {
        $jsonMember = $this->MemberService->toJson($Member);

        return new JsonResponse($jsonMember);
    }

   
   /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="Member@gmail.com"
     *               ),     
     *               @OA\Property(
     *                   property="password",
     *                   type="string",
     *                   example="123456789"
     *               ),
     *                   @OA\Property(
     *                   property="cin",
     *                   type="string",
     *                   example="123456789"
     *               ),     
     *               @OA\Property(
     *                   property="phone",
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
     * @Security(name="Bearer") 
     * @OA\Tag(name="Member")
     * @Route("/api/create/Member", methods={"POST"})
     */
    public function createMember(Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $data = json_decode($request->getContent(), true);
        $Member = new Member();
        $form = $this->createForm(MemberType::class, $Member, array('csrf_protection' => false));
        $Member->setPassword(
            $userPasswordHasher->hashPassword(
                    $Member,
                    $form->get('plainPassword')->getData()
                )
            );
        $form->submit($data);
        $Member = $this->MemberService->persist($Member);
        return new JsonResponse($this->MemberService->toJson($Member));

    }

    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="Member@gmail.com"
     *               ),     
     *               @OA\Property(
     *                   property="password",
     *                   type="string",
     *                   example="123456789"
     *               ),
     *                   @OA\Property(
     *                   property="cin",
     *                   type="string",
     *                   example="123456789"
     *               ),     
     *               @OA\Property(
     *                   property="phone",
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
     * @OA\Tag(name="Member")
     * @Route("/api/update/Member/{id}", methods={"PUT"})
     */
    public function updateMember(Request $request, Member $Member)
    {
        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(MemberType::class, $Member, array('csrf_protection' => false));
        $form->submit($data, false);

        $Member = $this->MemberService->persist($Member);
        return new JsonResponse($this->MemberService->toJson($Member));
    }

    /**
     * @OA\Tag(name="Member")
     * @Security(name="Bearer") 
     * @Route("/api/delete/Member/{id}", methods={"DELETE"})
     */
    public function deleteMember($id, Request $request)
    {
        $Member = $this->MemberService->get($id);
        try {
            $this->MemberService->remove($Member);
            $request->getSession()->getFlashBag()->add('success', 'Member supprimé avec succès !');
            return new JsonResponse('done');
        } catch (\Exception $exception) {
             $request->getSession()->getFlashBag()->add('danger', 'un ou plusieurs produit liés  à cette entité !');
             return new JsonResponse($exception->getMessage());
        }
       
    }
}
