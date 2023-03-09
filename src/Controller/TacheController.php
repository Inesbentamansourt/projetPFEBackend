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
use App\Service\TacheService;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use App\Entity\Tache;
use App\Entity\Project;


class TacheController extends AbstractController
{
    /**
     * @var TacheService
     */

    public function __construct(TacheService $TacheService , TacheRepository $TacheRepository)
    {
        $this->TacheService = $TacheService;
        $this->TacheRepository = $TacheRepository;
    }

    /**
     * @OA\Tag(name="Tache")
     * @Security(name="Bearer") 
     * @Route("/api/liste/Tache", methods={"GET"})
     */
    public function liste()
    {
        $liste = $this->TacheService->getTache();

        $jsonTache = [];
        foreach ($liste as $key => $Tache){
            $jsonTache[$key] = $this->TacheService->toJson($Tache);
        }

        return new JsonResponse($jsonTache);
    }

    /**
     * @OA\Tag(name="Tache")
     * @Security(name="Bearer") 
     * @Route("/api/Tache/{id}", methods={"GET"})
     */
    public function getTache(Tache $Tache)
    {
        $jsonTache = $this->TacheService->toJson($Tache);

        return new JsonResponse($jsonTache);
    }

   
    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="Tache"
     *               ),     
     *               @OA\Property(
     *                   property="description",
     *                   type="string",
     *                   example="Tache domains"
     *               ),
     *               @OA\Property(
     *                   property="estimate_start_date",
     *                   type="string",
     *                   example="2020-12-02"
     *               ),
     *                   @OA\Property(
     *                   property="estimate_end_date",
     *                   type="string",
     *                   example="2020-12-02"
     *               ),     
     *               @OA\Property(
     *                   property="achivement_start_date",
     *                   type="string",
     *                   example="2020-12-02"
     *               ),
     *               @OA\Property(
     *                   property="achivement_end_date",
     *                   type="string",
     *                   example="2020-12-02"
     *               ),
     *                @OA\Property(
     *                   property="evenement",
     *                   type="integer",                        
     *                   example="3"
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
     * @OA\Tag(name="Tache")
     * @Route("/api/create/Tache", methods={"POST"})
     */
    public function createTache(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $Tache = new Tache();
        $form = $this->createForm(TacheType::class, $Tache, array('csrf_protection' => false));
        $form->submit($data);
        $Tache = $this->TacheService->persist($Tache);
        return new JsonResponse($this->TacheService->toJson($Tache));

    }

    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="Tache"
     *               ),     
     *               @OA\Property(
     *                   property="description",
     *                   type="string",
     *                   example="Tache domains"
     *               ),
     *               @OA\Property(
     *                   property="estimate_start_date",
     *                   type="string",
     *                   example="2020-12-02"
     *               ),
     *                   @OA\Property(
     *                   property="estimate_end_date",
     *                   type="string",
     *                   example="2020-12-02"
     *               ),     
     *               @OA\Property(
     *                   property="achivement_start_date",
     *                   type="string",
     *                   example="2020-12-02"
     *               ),
     *               @OA\Property(
     *                   property="achivement_end_date",
     *                   type="string",
     *                   example="2020-12-02"
     *               ),
     *                 @OA\Property(
     *                   property="evenement",
     *                   type="integer",                        
     *                   example="3"
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
     * @OA\Tag(name="Tache")
     * @Route("/api/update/Tache/{id}", methods={"PUT"})
     */
    public function updateTache(Request $request, Tache $Tache)
    {
        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(TacheType::class, $Tache, array('csrf_protection' => false));
        $form->submit($data, false);

        $Tache = $this->TacheService->persist($Tache);
        return new JsonResponse($this->TacheService->toJson($Tache));
    }

    /**
     * @OA\Tag(name="Tache")
     * @Security(name="Bearer") 
     * @Route("/api/delete/Tache/{id}", methods={"DELETE"})
     */
    public function deleteTache($id, Request $request)
    {
        $Tache = $this->TacheService->get($id);
        try {
            $this->TacheService->remove($Tache);
            $request->getSession()->getFlashBag()->add('success', 'Tache supprimé avec succès !');
            return new JsonResponse('done');
        } catch (\Exception $exception) {
             $request->getSession()->getFlashBag()->add('danger', 'un ou plusieurs produit liés  à cette entité !');
             return new JsonResponse($exception->getMessage());
        }
       
    }
}
