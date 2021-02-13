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
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas...");
        }
        $categoryArticles = $category->getArticles();
        $array = $categoryArticles->toArray();
        $articles = array_reverse($array);

        $tags = $tagRepository->findAll();

        $categories = $categoryRepository->findAll();

        return $this->render('home/category_show.html.twig', [
            'category' => $category,
            'articles' => $articles,
            'tags' => $tags,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/tag/{name}", name="tag_show")
     */
    public function tagShow(TagRepository $tagRepository, $name): Response
    {
        $tag = $tagRepository->findOneBy(['name' => $name]);
        if (!$tag) {
            throw $this->createNotFoundException("L'étiquette demandée n'existe pas...");
        }
        $tagArticles = $tag->getArticles();
        $array = $tagArticles->toArray();
        $articles = array_reverse($array);
        $tags = $tagRepository->findAll();

        return $this->render('home/tag_show.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
            'tags' => $tags
        ]);
    }
}
