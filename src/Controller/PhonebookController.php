<?php

namespace App\Controller;

use App\Entity\Subdivision;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhonebookController extends AbstractController
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * PhonebookController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index(): Response
    {
        $subdivisions = $this->getDoctrine()
            ->getRepository(Subdivision::class)
            ->findAllIfDepartmentAndPhonesExist();

        return $this->render('phonebook/index.html.twig', [
            'subdivisions' => $subdivisions
        ]);
    }

    /**
     * @Route("/phones", name="list")
     * @return Response
     */
    public function list(): Response
    {
        $subdivisions = $this->getDoctrine()
            ->getRepository(Subdivision::class)
            ->findAllWithPhones();

        if (!$subdivisions) {
            throw $this->createNotFoundException($this->translator->trans('exception.subdivisions_not_found'));
        }

        return $this->render('phonebook/list.html.twig', [
            'subdivisions' => $subdivisions
        ]);
    }

    /**
     * @Route("/phones/{id}", name="listBy", requirements={"page"="\d+"})
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function listBy(int $id): Response
    {
        $subdivision = $this->getDoctrine()
            ->getRepository(Subdivision::class)
            ->findOneByIdWithPhones($id);

        if (!$subdivision) {
            throw $this->createNotFoundException($this->translator->trans('exception.subdivision_not_found'));
        }

        return $this->render('phonebook/list_by.html.twig', [
            'subdivision' => $subdivision,
        ]);
    }
}
