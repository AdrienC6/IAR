<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Query\FilterCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/{name}", name="category_show")
     */
    public function categoryShow(CategoryRepository $categoryRepository, $name, TagRepository $tagRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $name]);
        $categoryArticles = $category->getArticles();
        $array = $categoryArticles->toArray();
        $articles = array_reverse($array);
        $tags = $tagRepository->findAll();
        // dd($t);

        return $this->render('home/category_show.html.twig', [
            'category' => $category,
            'articles' => $articles,
            'tags' => $tags
        ]);
    }
}
