<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MainBundle:Default:index.html.twig');
    }

    public function coreAction(){


        echo "<pre>";
        $string = 'http://pokersites.com/dev2/tests/branching/real-money.php';


        //echo "Test";

        exit;

    }
}
