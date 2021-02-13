<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Form\TagType;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/article/nouveau", name="article_add")
     */
    public function articleAdd(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article;
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new DateTime());
            $em->persist($article);
            $em->flush();
        }
        return $this->render('admin/article_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/tous", name="article_all")
     */
    public function articleIndex(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render("admin/article_all.html.twig", [
            'articles' => $articles
        ]);
    }
}
