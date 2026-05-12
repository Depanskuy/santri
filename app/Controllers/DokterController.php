<?php
    namespace App\Controllers; 
    
    use App\Core\View;

    class DokterController
    {
        public function detail($id)
        {
            $data = require __DIR__ . "/../../data/dummy.php";

            $dokter = null;
            foreach ($data['doctors'] as $d){
                if ($d['id'] == $id){
                    $dokter = $d;
                }
            }

            if(!$dokter){
                http_response_code(400);
                echo "<h1> Dokter tidak ada </h1>";
                return;
            }

            View::render('dokter_detail', ['dokter' => $dokter]);
        }
    }

?>