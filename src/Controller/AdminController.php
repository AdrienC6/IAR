<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Form\TagType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

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
     * @Route("/admin/categorie/nouvelle", name="category_add")
     */
    public function categoryAdd(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
        }

        return $this->render("admin/category_add.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/tag/nouveau", name="tag_add")
     */
    public function tagAdd(Request $request, EntityManagerInterface $em): Response
    {
        $tag = new Tag;

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();
        }
        return $this->render("admin/tag_add.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
