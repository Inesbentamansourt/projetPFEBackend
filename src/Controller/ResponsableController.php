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
use App\Service\ResponsableService;
use App\Form\ResponsableType;
use App\Repository\ResponsableRepository;
use App\Entity\Responsable;



class ResponsableController extends AbstractController
{
    /**
     * @var ResponsableService
     */

    public function __construct(ResponsableService $ResponsableService , ResponsableRepository $ResponsableRepository)
    {
        $this->ResponsableService = $ResponsableService;
        $this->ResponsableRepository = $ResponsableRepository;
    }

    /**
     * @OA\Tag(name="Responsable")
     * @Security(name="Bearer") 
     * @Route("/api/liste/Responsable", methods={"GET"})
     */
    public function liste()
    {
        $liste = $this->ResponsableService->getResponsable();

        $jsonResponsable = [];
        foreach ($liste as $key => $Responsable){
            $jsonResponsable[$key] = $this->ResponsableService->toJson($Responsable);
        }

        return new JsonResponse($jsonResponsable);
    }

    /**
     * @OA\Tag(name="Responsable")
     * @Security(name="Bearer") 
     * @Route("/api/Responsable/{id}", methods={"GET"})
     */
    public function getResponsable(Responsable $Responsable)
    {
        $jsonResponsable = $this->ResponsableService->toJson($Responsable);

        return new JsonResponse($jsonResponsable);
    }

   
   /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="Responsable@gmail.com"
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
     *               @OA\Property(
     *                   property="evenement",
     *                   type="integer",
     *                   example="1"
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
     * @OA\Tag(name="Responsable")
     * @Route("/api/create/Responsable", methods={"POST"})
     */
    public function createResponsable(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $Responsable = new Responsable();
        $form = $this->createForm(ResponsableType::class, $Responsable, array('csrf_protection' => false));
        $form->submit($data);
        $Responsable = $this->ResponsableService->persist($Responsable);
        return new JsonResponse($this->ResponsableService->toJson($Responsable));

    }

    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="Responsable@gmail.com"
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
     *               @OA\Property(
     *                   property="evenement",
     *                   type="integer",
     *                   example="1"
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
     * @OA\Tag(name="Responsable")
     * @Route("/api/update/Responsable/{id}", methods={"PUT"})
     */
    public function updateResponsable(Request $request, Responsable $Responsable)
    {
        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(ResponsableType::class, $Responsable, array('csrf_protection' => false));
        $form->submit($data, false);

        $Responsable = $this->ResponsableService->persist($Responsable);
        return new JsonResponse($this->ResponsableService->toJson($Responsable));
    }

    /**
     * @OA\Tag(name="Responsable")
     * @Security(name="Bearer") 
     * @Route("/api/delete/Responsable/{id}", methods={"DELETE"})
     */
    public function deleteResponsable($id, Request $request)
    {
        $Responsable = $this->ResponsableService->get($id);
        try {
            $this->ResponsableService->remove($Responsable);
            $request->getSession()->getFlashBag()->add('success', 'Responsable supprimé avec succès !');
            return new JsonResponse('done');
        } catch (\Exception $exception) {
             $request->getSession()->getFlashBag()->add('danger', 'un ou plusieurs produit liés  à cette entité !');
             return new JsonResponse($exception->getMessage());
        }
       
    }
}
