<?php

namespace App\Controller;

use App\Form\CSVType;
use App\Repository\TagRepository;
use App\CustomServices\SearchService;
use App\Repository\ArticleRepository;
use App\Repository\BookingRepository;
use App\Repository\CategoryRepository;
use App\CustomServices\CSVImportService;
use App\Repository\CalendarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{

    public function __construct(CategoryRepository $categoryRepository, TagRepository $tagRepository, ArticleRepository $articleRepository, SearchService $searchService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->articleRepository = $articleRepository;
        $this->searchService = $searchService;
    }
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
     * @Route("/{name}", name="category_show", priority=-1)
     */
    public function categoryShow($name, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $category = $this->categoryRepository->findOneBy(['name' => $name]); // La catégorie à afficher sur la page
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas..."); // Si pas trouvée
        }
        $categoryArticles = $category->getArticles(); // Les articles de la catégorie
        $array = $categoryArticles->toArray(); // On en fait un array
        $articles = array_reverse($array); // On renverse l'array en DESC

        $tags = $this->tagRepository->findAll(); // On trouve tous les tags

        $form = $this->searchService->search($request)['form'];
        $searchResult = $this->searchService->search($request)['searchResult'];

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
    public function tagShow($name, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $tag = $this->tagRepository->findOneBy(['name' => $name]); // Tag à afficher sur la page
        if (!$tag) {
            throw $this->createNotFoundException("L'étiquette demandée n'existe pas..."); // Si pas trouvé
        }
        $tagArticles = $tag->getArticles(); // Articles du tag
        $array = $tagArticles->toArray(); // On transforme en array
        $articles = array_reverse($array); // On reverse en DESC

        $tags = $this->tagRepository->findAll(); // Tous les tags

        $form = $this->searchService->search($request)['form'];
        $searchResult = $this->searchService->search($request)['searchResult'];


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
    public function articleShow($id, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $article = $this->articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException("L'article demandé n'existe pas..."); // Si pas trouvé
        }

        $tagsArticleAll = $article->getTag();
        $array = $tagsArticleAll->toArray();
        $tagsArticle = array_reverse($array);

        $categoriesArticleAll = $article->getCategory();
        $array2 = $categoriesArticleAll->toArray();
        $categoriesArticle = array_reverse($array2);

        $articles = $this->articleRepository->findAll();

        $tagIndex = $this->tagRepository->findAll();

        $form = $this->searchService->search($request)['form'];
        $searchResult = $this->searchService->search($request)['searchResult'];

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

    /**
     * @Route("/calendrier", name="calendar_show", priority=1)
     */
    public function calendar(BookingRepository $bookingRepository, Request $request, CalendarRepository $calendar): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $events = $bookingRepository->findBy([], ['begin_at' => 'ASC']);
        foreach ($events as $event) {
            $categoryEvent = $event->getCategory();
            $array = $categoryEvent->toArray();
            $categories = array_reverse($array);
        }

        $events = $calendar->findAll();
        $meetings = [];
        foreach ($events as $event) {
            $categoriesEvent = $event->getCategories();
            $array = $categoriesEvent->toArray();
            $categories = array_reverse($array);
            $categoriesArray = [];
            foreach ($categories as $c) {
                $categoriesArray[] = $c->getName();
            }

            $tagsEvent = $event->getTags();
            $array = $tagsEvent->toArray();
            $tags = array_reverse($array);
            $tagsArray = [];
            foreach ($tags as $t) {
                $tagsArray[] = $t->getName();
            }

            $meetings[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'categories' => $categoriesArray,
                'tags' => $tagsArray,
                'urlEvent' => $event->getUrl(),
                'allDay' => $event->getAllDay(),
                'startDisplay' => $event->getStart()->format('d/m/Y H:i'),
                'endDisplay' => $event->getEnd()->format('d/m/Y H:i')
            ];
        }

        $data = json_encode($meetings);

        $tagIndex = $this->tagRepository->findAll();

        $form = $this->searchService->search($request)['form']->createView();
        $searchResult = $this->searchService->search($request)['searchResult'];

        return $this->render('home/calendar.html.twig',compact('events', 'form', 'searchResult', 'tagIndex', 'categories', 'data'));
    }

    /**
     * @Route("/csv", name="csv", priority=1)
     */
    public function csv(Request $request, CSVImportService $cSVImportService)
    {
        $user = $this->getUser();
        // dd($user->getRoles());
        if (!in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(CSVType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileName = $_FILES['csv']['name']['csv_file'];
            $fileTMP = $_FILES['csv']['tmp_name']['csv_file'];

            if (file_exists($fileName)) {
                unlink($_FILES['csv']['name']['csv_file']);
                move_uploaded_file($fileTMP, $fileName);
            } else {
                move_uploaded_file($fileTMP, $fileName);
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
