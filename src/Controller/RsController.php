<?php

namespace MyFatoorah\Symfony\Controller;


use Symfony\Component\HttpFoundation\Response;

class RsController {

    public function number(): Response {
        $number = random_int(0, 100);

        return new Response(
                '<html><body>Lucky number: ' . $number . '</body></html>'
        );
    }
    
    public static function testo() {
        return 333;
    }

}
