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
use App\Service\ReunionService;
use App\Form\ReunionType;
use App\Repository\ReunionRepository;
use App\Entity\Reunion;
use App\Entity\Project;


class ReunionController extends AbstractController
{
    /**
     * @var ReunionService
     */

    public function __construct(ReunionService $ReunionService , ReunionRepository $ReunionRepository)
    {
        $this->ReunionService = $ReunionService;
        $this->ReunionRepository = $ReunionRepository;
    }

    /**
     * @OA\Tag(name="Reunion")
     * @Security(name="Bearer") 
     * @Route("/api/liste/Reunion", methods={"GET"})
     */
    public function liste()
    {
        $liste = $this->ReunionService->getReunion();

        $jsonReunion = [];
        foreach ($liste as $key => $Reunion){
            $jsonReunion[$key] = $this->ReunionService->toJson($Reunion);
        }

        return new JsonResponse($jsonReunion);
    }

    /**
     * @OA\Tag(name="Reunion")
     * @Security(name="Bearer") 
     * @Route("/api/Reunion/{id}", methods={"GET"})
     */
    public function getReunion(Reunion $Reunion)
    {
        $jsonReunion = $this->ReunionService->toJson($Reunion);

        return new JsonResponse($jsonReunion);
    }

   
    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="Reunion"
     *               ),     
     *               @OA\Property(
     *                   property="description",
     *                   type="string",
     *                   example="Reunion description"
     *               ),
     *               @OA\Property(
     *                   property="start_date",
     *                   type="string",
     *                   example="2021-02-05"
     *               ),
     *                   @OA\Property(
     *                   property="end_date",
     *                   type="string",
     *                   example="2021-09-10"
     *               ),     
     *               @OA\Property(
     *                   property="piece_jointe",
     *                   type="string",
     *                   example="piece_jointe"
     *               ),
     *               @OA\Property(
     *                   property="url",
     *                   type="string",
     *                   example="url"
     *               ),
     *               @OA\Property(
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
     * @OA\Tag(name="Reunion")
     * @Route("/api/create/Reunion", methods={"POST"})
     */
    public function createReunion(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $Reunion = new Reunion();
        $form = $this->createForm(ReunionType::class, $Reunion, array('csrf_protection' => false));
        $form->submit($data);
        $Reunion = $this->ReunionService->persist($Reunion);
        return new JsonResponse($this->ReunionService->toJson($Reunion));

    }

    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="Reunion"
     *               ),     
     *               @OA\Property(
     *                   property="description",
     *                   type="string",
     *                   example="Reunion description"
     *               ),
     *               @OA\Property(
     *                   property="start_date",
     *                   type="string",
     *                   example="2021-02-05"
     *               ),
     *                   @OA\Property(
     *                   property="end_date",
     *                   type="string",
     *                   example="2021-09-10"
     *               ),     
     *               @OA\Property(
     *                   property="piece_jointe",
     *                   type="string",
     *                   example="piece_jointe"
     *               ),
     *               @OA\Property(
     *                   property="url",
     *                   type="string",
     *                   example="url"
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
     * @OA\Tag(name="Reunion")
     * @Route("/api/update/Reunion/{id}", methods={"PUT"})
     */
    public function updateReunion(Request $request, Reunion $Reunion)
    {
        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(ReunionType::class, $Reunion, array('csrf_protection' => false));
        $form->submit($data, false);

        $Reunion = $this->ReunionService->persist($Reunion);
        return new JsonResponse($this->ReunionService->toJson($Reunion));
    }

    /**
     * @OA\Tag(name="Reunion")
     * @Security(name="Bearer") 
     * @Route("/api/delete/Reunion/{id}", methods={"DELETE"})
     */
    public function deleteReunion($id, Request $request)
    {
        $Reunion = $this->ReunionService->get($id);
        try {
            $this->ReunionService->remove($Reunion);
            $request->getSession()->getFlashBag()->add('success', 'Reunion supprimé avec succès !');
            return new JsonResponse('done');
        } catch (\Exception $exception) {
             $request->getSession()->getFlashBag()->add('danger', 'un ou plusieurs produit liés  à cette entité !');
             return new JsonResponse($exception->getMessage());
        }
       
    }
}
