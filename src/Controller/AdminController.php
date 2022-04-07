<?php

namespace App\Controller;

use App\Entity\Dogs;
use App\Form\DogsType;
use App\Repository\DogsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index( Request $request, DogsRepository $DogsRepository): Response
    {
      $dog = new Dogs();
      $user = $this->getUser();
      $properties = $DogsRepository->findAll();
      $form = $this->createForm(DogsType::class, $dog);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $dog->setUser($user);
          $entityManager->persist($dog);
          $entityManager->flush();

          return $this->redirectToRoute('home');
        }
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'dogForm' => $form->createView(),
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete( Request $request, DogsRepository $DogsRepository, int $id)
    {
      $properties = $DogsRepository->find($id);
      $em = $this->getDoctrine()->getManager();
      $em->remove($properties);
      $em->flush();
        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit( Request $request, DogsRepository $DogsRepository, int $id, Dogs $dogs)
    {
      $dog = new Dogs();
      $product =  $DogsRepository->find($id);
      $form = $this->createForm(DogsType::class, $dog);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $dogs = $form->getData();
        $product->setName($dogs->getName())->setRace($dogs->getRace());

        $em = $this->getDoctrine()->getManager();
          $em->flush();

          return $this->redirectToRoute('home');
        }
        return $this->render('admin/edit.html.twig', [
            'dogs' => $dogs,
            'dogForm' => $form->createView(),
        ]);

    }
}
