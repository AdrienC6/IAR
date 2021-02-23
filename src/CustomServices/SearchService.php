<?php

namespace App\CustomServices;

use App\Form\SearchContentType;
use App\Repository\TagRepository;
use App\Repository\ArticleRepository;
use App\Repository\CalendarRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchService extends AbstractController
{
    protected $articleRepository;
    protected $categoryRepository;
    protected $tagRepository;
    protected $calendarRepository;

    public function __construct(ArticleRepository $articleRepository, TagRepository $tagRepository, CategoryRepository $categoryRepository, CalendarRepository $calendarRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->calendarRepository = $calendarRepository;
    }

    public function search($request)
    {
        $form = $this->createForm(SearchContentType::class);
        $form->handleRequest($request);

        $searchResult = [];
        $eventsResult = [];

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('words')->getData() != null) { // Si champ pas vide

                if ($form->get('category')->getData() != null) {

                    if ($form->get('tag')->getData() != null) {
                        $articlesTag = array_reverse($this->tagRepository->findOneBy(['name' => $form->get('tag')->getData()->getName()])->getArticles()->toArray());
                        $articlesCategory = array_reverse($this->categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getArticles()->toArray());

                        foreach ($articlesCategory as $a) {
                            foreach ($this->articleRepository->search($form->get('words')->getData()) as $a2) {
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
                        $articlesCategory = array_reverse($this->categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getArticles()->toArray());

                        // On les ajoute tous au résultat
                        foreach ($articlesCategory as $a) {
                            foreach ($this->articleRepository->search($form->get('words')->getData()) as $a2) {
                                $a3 = $a2->getTitle();
                                if ($a3 == $a->getTitle()) {
                                    $searchResult[] = $a;
                                }
                            }
                        }
                    }
                } else {
                    $searchResult = $this->articleRepository->search($form->get('words')->getData());
                }

                if ($form->get('category')->getData() != null) {

                    if ($form->get('tag')->getData() != null) {
                        $eventsTag = array_reverse($this->tagRepository->findOneBy(['name' => $form->get('tag')->getData()->getName()])->getCalendars()->toArray());
                        $eventsCategory = array_reverse($this->categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getCalendars()->toArray());

                        foreach ($eventsCategory as $a) {
                            foreach ($this->calendarRepository->search($form->get('words')->getData()) as $a2) {
                                foreach ($eventsTag as $a4) {
                                    $a5 = $a4->getTitle();
                                    $a3 = $a2->getTitle();
                                    if ($a3 == $a->getTitle() && $a->getTitle() == $a5) {
                                        $eventsResult[] = $a;
                                    }
                                }
                            }
                        }
                    } else {
                        // On retrouve tous les articles liés à la category
                        $eventsCategory = array_reverse($this->categoryRepository->findOneBy(['name' => $form->get('category')->getData()->getName()])->getCalendars()->toArray());

                        // On les ajoute tous au résultat
                        foreach ($eventsCategory as $a) {
                            foreach ($this->calendarRepository->search($form->get('words')->getData()) as $a2) {
                                $a3 = $a2->getTitle();
                                if ($a3 == $a->getTitle()) {
                                    $eventsResult[] = $a;
                                }
                            }
                        }
                    }
                } else {
                    $eventsResult = $this->calendarRepository->search($form->get('words')->getData());
                }

            }
        }


        return ['searchResult' => $searchResult, 'form' => $form, 'eventsResult'=>$eventsResult];
    }
}
