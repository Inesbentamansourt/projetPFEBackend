<?php
namespace App\Controller;

use App\Entity\UploadFile;
use App\Form\UploadFileType;
use App\Service\UploadFileService;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class UploadFileController extends AbstractController
{
    
    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
    * @Route("/new/file", name="test_file")
    */
    public function new(Request $request, UploadFileService $UploadFileService)
    {
        $UploadFile = new UploadFile();
        $form = $this->createForm(UploadFileType::class, $UploadFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $form->get('brochurefilename')->getData(); 
            $UploadFileService->upload($myFile);
            $UploadFile->setBrochurefilename($myFile);
            $this->manager->persist($UploadFile);
            $this->manager->flush();
        }


        return $this->render('UploadFile/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}