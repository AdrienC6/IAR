<?php

namespace App\Controller;


use App\Form\CSVType;
use App\CustomServices\CSVImportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/csv", name="csv", priority=1)
     */
    public function csv(Request $request, CSVImportService $cSVImportService)
    {
        $form = $this->createForm(CSVType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileName = $_FILES['csv']['name']['csv_file'];
            $fileTMP = $_FILES['csv']['tmp_name']['csv_file'];

            if (file_exists('CSV/'.$fileName)) {
                unlink('CSV/'.$fileName);
                move_uploaded_file($fileTMP, 'CSV/'.$fileName);
            } else {
                move_uploaded_file($fileTMP, 'CSV/'.$fileName);
            }

            $cSVImportService->getDataFromFile();
            $cSVImportService->createUsers();

            return $this->redirectToRoute('admin');
        }

        return $this->render("admin/csv_import.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
