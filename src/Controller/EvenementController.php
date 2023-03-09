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
use App\Service\EvenementService;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;



class EvenementController extends AbstractController
{
    /**
     * @var EvenementService
     */

    public function __construct(EvenementService $EvenementService , EvenementRepository $EvenementRepository)
    {
        $this->EvenementService = $EvenementService;
        $this->EvenementRepository = $EvenementRepository;
    }

    /**
     * @OA\Tag(name="Evenement")
     * @Security(name="Bearer") 
     * @Route("/api/liste/Evenement", methods={"GET"})
     */
    public function liste()
    {
        $liste = $this->EvenementService->getEvenement();

        $jsonEvenement = [];
        foreach ($liste as $key => $Evenement){
            $jsonEvenement[$key] = $this->EvenementService->toJson($Evenement);
        }

        return new JsonResponse($jsonEvenement);
    }

    /**
     * @OA\Tag(name="Evenement")
     * @Security(name="Bearer") 
     * @Route("/api/Evenement/{id}", methods={"GET"})
     */
    public function getEvenement(Evenement $Evenement)
    {
        $jsonEvenement = $this->EvenementService->toJson($Evenement);

        return new JsonResponse($jsonEvenement);
    }

   
   /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="Evenement"
     *               ),     
     *               @OA\Property(
     *                   property="description",
     *                   type="string",
     *                   example="Evenement domains"
     *               ),
     *                   @OA\Property(
     *                   property="start_date",
     *                   type="string",
     *                   example="2022-03-12"
     *               ),     
     *               @OA\Property(
     *                   property="end_date",
     *                   type="string",
     *                   example="2022-09-15"
     *               ),    
     *               @OA\Property(
     *                   property="statut",
     *                   type="string",
     *                   example="evenement statut"
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
     * @OA\Tag(name="Evenement")
     * @Route("/api/create/Evenement", methods={"POST"})
     */
    public function createEvenement(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $Evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $Evenement, array('csrf_protection' => false));
        $form->submit($data);
        $Evenement = $this->EvenementService->persist($Evenement);
        return new JsonResponse($this->EvenementService->toJson($Evenement));

    }

    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="Evenement"
     *               ),     
     *               @OA\Property(
     *                   property="description",
     *                   type="string",
     *                   example="Evenement domains"
     *               ),
     *                   @OA\Property(
     *                   property="start_date",
     *                   type="string",
     *                   example="2022-03-12"
     *               ),     
     *               @OA\Property(
     *                   property="end_date",
     *                   type="string",
     *                   example="2022-09-15"
     *               ),    
     *               @OA\Property(
     *                   property="statut",
     *                   type="string",
     *                   example="evenement statut"
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
     * @OA\Tag(name="Evenement")
     * @Route("/api/update/Evenement/{id}", methods={"PUT"})
     */
    public function updateEvenement(Request $request, Evenement $Evenement)
    {
        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(EvenementType::class, $Evenement, array('csrf_protection' => false));
        $form->submit($data, false);

        $Evenement = $this->EvenementService->persist($Evenement);
        return new JsonResponse($this->EvenementService->toJson($Evenement));
    }

    /**
     * @OA\Tag(name="Evenement")
     * @Security(name="Bearer") 
     * @Route("/api/delete/Evenement/{id}", methods={"DELETE"})
     */
    public function deleteEvenement($id, Request $request)
    {
        $Evenement = $this->EvenementService->get($id);
        try {
            $this->EvenementService->remove($Evenement);
            $request->getSession()->getFlashBag()->add('success', 'Evenement supprimé avec succès !');
            return new JsonResponse('done');
        } catch (\Exception $exception) {
             $request->getSession()->getFlashBag()->add('danger', 'un ou plusieurs produit liés  à cette entité !');
             return new JsonResponse($exception->getMessage());
        }
       
    }
}
