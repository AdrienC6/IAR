<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\SearchContentType;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/{name}", name="category_show")
     */
    public function categoryShow(CategoryRepository $categoryRepository, $name, TagRepository $tagRepository, Request $request, ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $category = $categoryRepository->findOneBy(['name' => $name]); // La catégorie à afficher sur la page
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas..."); // Si pas trouvée
        }
        $categoryArticles = $category->getArticles(); // Les articles de la catégorie
        $array = $categoryArticles->toArray(); // On en fait un array
        $articles = array_reverse($array); // On renverse l'array en DESC

        $tags = $tagRepository->findAll(); // On trouve tous les tags

        $form = $this->createForm(SearchContentType::class);
        $form->handleRequest($request);

        $searchResult = [];

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('words')->getData() != null) { // Si champ pas vide
                if ($form->get('category')->getData() != null) {

                    // On retrouve tous les articles liés à la category
                    $articlesCategory = array_reverse($categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getArticles()->toArray());

                    // On les ajoute tous au résultat
                    foreach ($articlesCategory as $a) {
                        foreach ($articleRepository->search($form->get('words')->getData()) as $a2) {
                            $a3 = $a2->getTitle();
                            if ($a3 == $a->getTitle()) {
                                $searchResult[] = $a;
                            }
                        }
                    }
                } else {
                    $searchResult = $articleRepository->search($form->get('words')->getData());
                }
            }
        }

        return $this->render('home/category_show.html.twig', [
            'category' => $category,
            'articles' => $articles,
            'tags' => $tags,
            'searchResult' => $searchResult,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/tag/{name}", name="tag_show")
     */
    public function tagShow(TagRepository $tagRepository, $name, CategoryRepository $categoryRepository, Request $request, ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $tag = $tagRepository->findOneBy(['name' => $name]); // Tag à afficher sur la page
        if (!$tag) {
            throw $this->createNotFoundException("L'étiquette demandée n'existe pas..."); // Si pas trouvé
        }
        $tagArticles = $tag->getArticles(); // Articles du tag
        $array = $tagArticles->toArray(); // On transforme en array
        $articles = array_reverse($array); // On reverse en DESC

        $tags = $tagRepository->findAll(); // Tous les tags

        $form = $this->createForm(SearchContentType::class);
        $form->handleRequest($request);

        $searchResult = [];

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('words')->getData() != null) { // Si champ pas vide

                if ($form->get('category')->getData() != null) {

                    if ($form->get('tag')->getData() != null) {
                        $articlesTag = array_reverse($tagRepository->findOneBy(['name' => $form->get('tag')->getData()->getName()])->getArticles()->toArray());
                        $articlesCategory = array_reverse($categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getArticles()->toArray());

                        foreach ($articlesCategory as $a) {
                            foreach ($articleRepository->search($form->get('words')->getData()) as $a2) {
                                foreach ($articlesTag as $a4) {
                                    $a5 = $a4->getTitle();
                                    $a3 = $a2->getTitle();
                                    if ($a3 == $a->getTitle() && $a->getTitle() == $a5) {
                                        $searchResult[] = $a;
                                    }
                                }
                            }
                        }
                    } else {
                        // On retrouve tous les articles liés à la category
                        $articlesCategory = array_reverse($categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getArticles()->toArray());

                        // On les ajoute tous au résultat
                        foreach ($articlesCategory as $a) {
                            foreach ($articleRepository->search($form->get('words')->getData()) as $a2) {
                                $a3 = $a2->getTitle();
                                if ($a3 == $a->getTitle()) {
                                    $searchResult[] = $a;
                                }
                            }
                        }
                    }
                } else {
                    $searchResult = $articleRepository->search($form->get('words')->getData());
                }
            }
        }

        return $this->render('home/tag_show.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
            'tags' => $tags,
            'form' => $form->createView(),
            'searchResult' => $searchResult
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function articleShow(ArticleRepository $articleRepository, $id, Request $request, TagRepository $tagRepository, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException("L'article demandé n'existe pas..."); // Si pas trouvé
        }

        $tagsArticleAll = $article->getTag();
        $array = $tagsArticleAll->toArray();
        $tagsArticle = array_reverse($array);

        $categoriesArticleAll = $article->getCategory();
        $array2 = $categoriesArticleAll->toArray();
        $categoriesArticle = array_reverse($array2);

        $articles = $articleRepository->findAll();

        $tagIndex = $tagRepository->findAll();

        $form = $this->createForm(SearchContentType::class);
        $form->handleRequest($request);

        $searchResult = [];

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('words')->getData() != null) { // Si champ pas vide

                if ($form->get('category')->getData() != null) {

                    if ($form->get('tag')->getData() != null) {
                        $articlesTag = array_reverse($tagRepository->findOneBy(['name' => $form->get('tag')->getData()->getName()])->getArticles()->toArray());
                        $articlesCategory = array_reverse($categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getArticles()->toArray());

                        foreach ($articlesCategory as $a) {
                            foreach ($articleRepository->search($form->get('words')->getData()) as $a2) {
                                foreach ($articlesTag as $a4) {
                                    $a5 = $a4->getTitle();
                                    $a3 = $a2->getTitle();
                                    if ($a3 == $a->getTitle() && $a->getTitle() == $a5) {
                                        $searchResult[] = $a;
                                    }
                                }
                            }
                        }
                    } else {
                        // On retrouve tous les articles liés à la category
                        $articlesCategory = array_reverse($categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getArticles()->toArray());

                        // On les ajoute tous au résultat
                        foreach ($articlesCategory as $a) {
                            foreach ($articleRepository->search($form->get('words')->getData()) as $a2) {
                                $a3 = $a2->getTitle();
                                if ($a3 == $a->getTitle()) {
                                    $searchResult[] = $a;
                                }
                            }
                        }
                    }
                } else {
                    $searchResult = $articleRepository->search($form->get('words')->getData());
                }
            }
        }

        return $this->render('home/article_show.html.twig', [
            'article' => $article,
            'tags' => $tagsArticle,
            'categories' => $categoriesArticle,
            'searchResult' => $searchResult,
            'form' => $form->createView(),
            'articles' => $articles,
            'tagIndex' => $tagIndex
        ]);
    }
}
