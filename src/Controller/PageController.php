<?php
namespace App\Controller;
use App\Repository\PollRepository;
use App\Repository\CategoryRepository;

class PageController extends Controller {
    public function home() {
        // TODO : récupérer les derniers sondages (limit 3)
        $pollRepository = new PollRepository();
        $polls = $pollRepository->findAll(3);
        $this->render('page/home', [
            'polls' => $polls
        ]);
    }

    public function about() {
        $this->render('page/about');
    }   

    public function legal() {
        $this->render('page/legal');
    }

    public function categories() {
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();
        $this->render('page/categories', [
            'categories' => $categories
        ]);
    }
}
