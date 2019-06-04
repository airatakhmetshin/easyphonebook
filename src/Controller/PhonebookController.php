<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PhonebookController extends AbstractController
{
    /**
     * @Route("/phonebook", name="phonebook")
     */
    public function index()
    {
        return $this->render('phonebook/index.html.twig', [
            'controller_name' => 'PhonebookController',
        ]);
    }
}
