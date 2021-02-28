<?php

namespace App\Controller;

use App\Repository\TagRepository;
use App\CustomServices\SearchService;
use App\Repository\ArchiveRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CalendarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    protected $categoryRepository;
    protected $tagRepository;
    protected $articleRepository;
    protected $searchService;
    
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
        $user = $this->getUser(); // Si user pas connecté -> redirection
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getVerified() != true) { // Si pas vérifié (= mdp changé) -> on force le changement de mdp
            $this->addFlash('success', 'Vous devez changer votre mot de passe lors de votre première connexion. Pour rappel, votre mot de passe actuel est "gircor".');
            return $this->redirectToRoute('user_edit_pw');
        }

        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/{name}", name="category_show", priority=-1)
     */
    public function categoryShow($name, Request $request): Response
    {
        $user = $this->getUser(); // Si user pas connecté -> redirection
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getVerified() != true) { // Si pas vérifié (= mdp changé) -> on force le changement de mdp
            $this->addFlash('success', 'Vous devez changer votre mot de passe lors de votre première connexion. Pour rappel, votre mot de passe actuel est "gircor".');
            return $this->redirectToRoute('user_edit_pw');
        }

        $category = $this->categoryRepository->findOneBy(['name' => $name]); // La catégorie à afficher sur la page
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas..."); // Si pas trouvée
        }
        
        $articles = array_reverse($category->getArticles()->toArray()); // Les articles de la catégorie en array reverse

        $tags = $this->tagRepository->findAll(); // On trouve tous les tags

        $form = $this->searchService->search($request)['form'];
        $searchResult = $this->searchService->search($request)['searchResult'];
        $eventsResult = $this->searchService->search($request)['eventsResult'];

        return $this->render('home/category_show.html.twig', [
            'category' => $category,
            'articles' => $articles,
            'tags' => $tags,
            'searchResult' => $searchResult,
            'form' => $form->createView(),
            'eventsResult' => $eventsResult
        ]);
    }

    /**
     * @Route("/tag/{name}", name="tag_show")
     */
    public function tagShow($name, Request $request): Response
    {
        $user = $this->getUser(); // Si user pas connecté -> redirection
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getVerified() != true) { // Si pas vérifié (= mdp changé) -> on force le changement de mdp
            $this->addFlash('success', 'Vous devez changer votre mot de passe lors de votre première connexion. Pour rappel, votre mot de passe actuel est "gircor".');
            return $this->redirectToRoute('user_edit_pw');
        }

        $tag = $this->tagRepository->findOneBy(['name' => $name]); // Tag à afficher sur la page
        if (!$tag) {
            throw $this->createNotFoundException("L'étiquette demandée n'existe pas..."); // Si pas trouvé
        }
        $articles = array_reverse($tag->getArticles()->toArray()); // Les articles du tag en array reverse

        $tags = $this->tagRepository->findAll(); // Tous les tags

        $form = $this->searchService->search($request)['form'];
        $searchResult = $this->searchService->search($request)['searchResult'];
        $eventsResult = $this->searchService->search($request)['eventsResult'];

        return $this->render('home/tag_show.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
            'tags' => $tags,
            'form' => $form->createView(),
            'searchResult' => $searchResult,
            'eventsResult' => $eventsResult
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function articleShow($id, Request $request): Response
    {
        $user = $this->getUser(); // Si user pas connecté -> redirection
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getVerified() != true) { // Si pas vérifié (= mdp changé) -> on force le changement de mdp
            $this->addFlash('success', 'Vous devez changer votre mot de passe lors de votre première connexion. Pour rappel, votre mot de passe actuel est "gircor".');
            return $this->redirectToRoute('user_edit_pw');
        }

        $article = $this->articleRepository->find($id); 

        if (!$article) {
            throw $this->createNotFoundException("L'article demandé n'existe pas..."); // Si pas trouvé
        }
        
        $tagsArticle = array_reverse($article->getTag()->toArray()); // Les tags de l'article en array reverse
        $categoriesArticle = array_reverse($article->getCategory()->toArray()); // Les catégories de l'article en array reverse
        $articles = $this->articleRepository->findAll();
        $tagIndex = $this->tagRepository->findAll();

        $form = $this->searchService->search($request)['form'];
        $searchResult = $this->searchService->search($request)['searchResult'];
        $eventsResult = $this->searchService->search($request)['eventsResult'];

        return $this->render('home/article_show.html.twig', [
            'article' => $article,
            'tags' => $tagsArticle,
            'categories' => $categoriesArticle,
            'searchResult' => $searchResult,
            'form' => $form->createView(),
            'articles' => $articles,
            'tagIndex' => $tagIndex,
            'eventsResult'=>$eventsResult
        ]);
    }

    /**
     * @Route("/calendrier", name="calendar_show", priority=1)
     */
    public function calendar(Request $request, CalendarRepository $calendar): Response
    {
        $user = $this->getUser(); // Si user pas connecté -> redirection
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getVerified() != true) { // Si pas vérifié (= mdp changé) -> on force le changement de mdp
            $this->addFlash('success', 'Vous devez changer votre mot de passe lors de votre première connexion. Pour rappel, votre mot de passe actuel est "gircor".');
            return $this->redirectToRoute('user_edit_pw');
        }

        $events = $calendar->findAll();
        $meetings = [];

        foreach ($events as $event) { 
            $categories = array_reverse($event->getCategories()->toArray()); // On récupère les catégories de l'event en array reverse
            $categoriesArray = [];
            $tagsArray = [];

            foreach ($categories as $c) {
                $categoriesArray[] = $c->getName(); // On ajoute le nom de la catégorie
            }

            $tags = array_reverse($event->getTags()->toArray()); // On récupère les tags de la même façon
            foreach ($tags as $t) {
                $tagsArray[] = $t->getName(); // Puis on les ajoute
            }

            $meetings[] = [ // Ensemble des events avec leur propriétés à afficher dans le calendar js
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
        $eventsResult = $this->searchService->search($request)['eventsResult'];


        return $this->render('home/calendar.html.twig',compact('events', 'form', 'searchResult', 'tagIndex', 'categories', 'data', 'eventsResult'));
    }

    /**
     * @Route("/archives", name="archive_index")
     */
    public function archiveIndex(ArchiveRepository $archiveRepository, Request $request):Response
    {
        $user = $this->getUser(); // Si user pas connecté -> redirection
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getVerified() != true) { // Si pas vérifié (= mdp changé) -> on force le changement de mdp
            $this->addFlash('success', 'Vous devez changer votre mot de passe lors de votre première connexion. Pour rappel, votre mot de passe actuel est "gircor".');
            return $this->redirectToRoute('user_edit_pw');
        }

        $archives = $archiveRepository->findAll();

        $articles = $this->articleRepository->findAll();
        $tagIndex = $this->tagRepository->findAll();

        $form = $this->searchService->search($request)['form'];
        $searchResult = $this->searchService->search($request)['searchResult'];
        $eventsResult = $this->searchService->search($request)['eventsResult'];


        return $this->render("home/archives.html.twig", [
            'archives'=>$archives,
            'articles' => $articles,
            'tagIndex' => $tagIndex,
            'searchResult'=>$searchResult,
            'eventsResult'=>$eventsResult,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/mentionslegales", name="mentions")
     */
    public function mentions():Response
    {
        return $this->render('home/mentions.html.twig');
    }
    
}
