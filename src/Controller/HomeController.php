<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DogsRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(DogsRepository $DogsRepository): Response
    {
      $user =  $this->getUser();
      $properties = $DogsRepository->findAll();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'dogs' => $properties
        ]);

    }
}
