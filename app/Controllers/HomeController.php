<?php
    namespace App\Controllers; 
    
    use App\Core\View;
    use App\Model\Poli;
    class HomeController
    {
        public function index()
        {
            $poli = Poli::all(true);
            View::render('home/index', ["poli" => $poli]);
        }
    }

?>