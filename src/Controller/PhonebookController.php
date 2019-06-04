<?php

namespace App\Controller;

use App\Entity\Subdivision;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhonebookController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index(): Response
    {
        $subdivisions = $this->getDoctrine()
            ->getRepository(Subdivision::class)
            ->findBy([], ['priority' => 'DESC']);

        return $this->render('phonebook/index.html.twig', [
            'subdivisions' => $subdivisions
        ]);
    }

    /**
     * @Route("/list/all", name="list")
     * @return Response
     */
    public function list(): Response
    {
        $subdivisions = $this->getDoctrine()
            ->getRepository(Subdivision::class)
            ->findBy([], ['priority' => 'DESC']);

        return $this->render('phonebook/list.html.twig', [
            'subdivisions' => $subdivisions
        ]);
    }

    /**
     * @Route("/list/{id}", name="listBy", requirements={"page"="\d+"})
     * @param int $id
     * @return Response
     */
    public function listBy(int $id): Response
    {
        $subdivision = $this->getDoctrine()
            ->getRepository(Subdivision::class)
            ->find($id);

        return $this->render('phonebook/list.html.twig', [
            'subdivisions' => [$subdivision],
        ]);
    }
}
