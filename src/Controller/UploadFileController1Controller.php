<?php

namespace App\Controller;

use App\Entity\UploadFile;
use App\Form\UploadFile1Type;
use App\Repository\UploadFileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/upload/file/controller1")
 */
class UploadFileController1Controller extends AbstractController
{
    /**
     * @Route("/", name="app_upload_file_controller1_index", methods={"GET"})
     */
    public function index(UploadFileRepository $uploadFileRepository): Response
    {
        return $this->render('upload_file_controller1/index.html.twig', [
            'upload_files' => $uploadFileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_upload_file_controller1_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UploadFileRepository $uploadFileRepository): Response
    {
        $uploadFile = new UploadFile();
        $form = $this->createForm(UploadFile1Type::class, $uploadFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadFileRepository->add($uploadFile, true);

            return $this->redirectToRoute('app_upload_file_controller1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('upload_file_controller1/new.html.twig', [
            'upload_file' => $uploadFile,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_upload_file_controller1_show", methods={"GET"})
     */
    public function show(UploadFile $uploadFile): Response
    {
        return $this->render('upload_file_controller1/show.html.twig', [
            'upload_file' => $uploadFile,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_upload_file_controller1_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UploadFile $uploadFile, UploadFileRepository $uploadFileRepository): Response
    {
        $form = $this->createForm(UploadFile1Type::class, $uploadFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadFileRepository->add($uploadFile, true);

            return $this->redirectToRoute('app_upload_file_controller1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('upload_file_controller1/edit.html.twig', [
            'upload_file' => $uploadFile,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_upload_file_controller1_delete", methods={"POST"})
     */
    public function delete(Request $request, UploadFile $uploadFile, UploadFileRepository $uploadFileRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$uploadFile->getId(), $request->request->get('_token'))) {
            $uploadFileRepository->remove($uploadFile, true);
        }

        return $this->redirectToRoute('app_upload_file_controller1_index', [], Response::HTTP_SEE_OTHER);
    }
}
