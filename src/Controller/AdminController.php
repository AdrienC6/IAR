<?php

namespace App\Controller;


use App\Form\CSVType;
use App\CustomServices\CSVImportService;
use App\Entity\Archive;
use App\Form\ArchiveType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/csv", name="csv", priority=1)
     */
    public function csv(Request $request, CSVImportService $cSVImportService, SluggerInterface $slugger) : Response
    {
        $form = $this->createForm(CSVType::class);
        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

        //     $fileName = $_FILES['csv']['name']['csv_file'];
        //     $fileTMP = $_FILES['csv']['tmp_name']['csv_file'];

        //     if (file_exists('CSV/'.'users')) {
        //         unlink('CSV/'.'users');
        //         move_uploaded_file($fileTMP, 'CSV/'.'users');
        //     } else {
        //         move_uploaded_file($fileTMP, 'CSV/'.'users');
        //     }

        //     $cSVImportService->getDataFromFile();
        //     $cSVImportService->createUsers();

        //     return $this->redirectToRoute('admin');
        // }

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csv_file')->getData();

            if ($csvFile) {
                // $originalFilename = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
                // $safeFilename = $slugger->slug($originalFilename);
                // $newFileName = $safeFilename . '-' . uniqid() . '.' . $csvFile->guessExtension();

                try {
                    $csvFile->move(
                        $this->getParameter('csv_directory'),
                        'users.csv'
                    );
                } catch (FileException $e) {
                }

                $cSVImportService->getDataFromFile();
                $cSVImportService->createUsers();
                
                return $this->redirectToRoute('admin');
            }
        }

        return $this->render("admin/csv_import.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/pdf", name="pdf", priority=1)
     */
    public function pdfImport(Request $request, SluggerInterface $slugger, EntityManagerInterface $em):Response{

        $archive = new Archive;

        $form = $this->createForm(ArchiveType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $csvFile = $form->get('pdfFileName')->getData();

            if($csvFile){
                $originalFilename = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFileName = $safeFilename.'-'.uniqid().'.'.$csvFile->guessExtension();

                try {
                    $csvFile->move(
                        $this->getParameter('pdf_directory'),
                        $newFileName
                    );
                } catch (FileException $e){

                }

                $archive->setPdfFileName($newFileName);
                $archive->setTitle($form->get('title')->getData());
                $archive->setYear($form->get('year')->getData());

                $em->persist($archive);
                $em->flush();

                return $this->redirectToRoute('admin');

            }
        }

        return $this->render("admin/pdf_import.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
