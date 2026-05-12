<?php
    namespace App\Core;
    
    class View {
        public static function render(string $view, array $data = []): void
        {
            $file = __DIR__. "/../View/" . $view . '.php';
            if(!file_exists($file)){
                throw new \RuntimeException("View tidak ada!");
            }

            extract($data, EXTR_SKIP);
            require $file;
        }
    }
    

?>