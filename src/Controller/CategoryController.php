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
use App\Service\CategoryService;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Entity\Category;



class CategoryController extends AbstractController
{
    /**
     * @var CategoryService
     */

    public function __construct(CategoryService $CategoryService , CategoryRepository $CategoryRepository)
    {
        $this->CategoryService = $CategoryService;
        $this->CategoryRepository = $CategoryRepository;
    }

    /**
     * @OA\Tag(name="Category")
     * @Security(name="Bearer") 
     * @Route("/api/liste/Category", methods={"GET"})
     */
    public function liste()
    {
        $liste = $this->CategoryService->getCategory();

        $jsonCategory = [];
        foreach ($liste as $key => $Category){
            $jsonCategory[$key] = $this->CategoryService->toJson($Category);
        }

        return new JsonResponse($jsonCategory);
    }

    /**
     * @OA\Tag(name="Category")
     * @Security(name="Bearer") 
     * @Route("/api/Category/{id}", methods={"GET"})
     */
    public function getCategory(Category $Category)
    {
        $jsonCategory = $this->CategoryService->toJson($Category);

        return new JsonResponse($jsonCategory);
    }

   
   /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="Category"
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
     * @OA\Response(
     *   response="401",
     *       description="Unauthorized",
     * )
     * @Security(name="Bearer") 
     * @OA\Tag(name="Category")
     * @Route("/api/create/Category", methods={"POST"})
     */
    public function createCategory(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $Category = new Category();
        $form = $this->createForm(CategoryType::class, $Category, array('csrf_protection' => false));
        $form->submit($data);
        $Category = $this->CategoryService->persist($Category);
        return new JsonResponse($this->CategoryService->toJson($Category));

    }

    /**
     *@OA\RequestBody( required=true, @OA\JsonContent (
     *               @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="Category"
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
     * @OA\Response(
     *   response="401",
     *       description="Unauthorized",
     * )
     * @Security(name="Bearer") 
     * @OA\Tag(name="Category")
     * @Route("/api/update/Category/{id}", methods={"PUT"})
     */
    public function updateCategory(Request $request, Category $Category)
    {
        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(CategoryType::class, $Category, array('csrf_protection' => false));
        $form->submit($data, false);

        $Category = $this->CategoryService->persist($Category);
        return new JsonResponse($this->CategoryService->toJson($Category));
    }

    /**
     * @OA\Tag(name="Category")
     * @Security(name="Bearer") 
     * @Route("/api/delete/Category/{id}", methods={"DELETE"})
     */
    public function deleteCategory($id, Request $request)
    {
        $Category = $this->CategoryService->get($id);
        try {
            $this->CategoryService->remove($Category);
            $request->getSession()->getFlashBag()->add('success', 'Category supprimé avec succès !');
            return new JsonResponse('done');
        } catch (\Exception $exception) {
             $request->getSession()->getFlashBag()->add('danger', 'un ou plusieurs produit liés  à cette entité !');
             return new JsonResponse($exception->getMessage());
        }
       
    }
}
