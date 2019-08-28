<?php

namespace App\Controller;

use App\Entity\Subdivision;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

    /**
     * @Route("/getpdf", name="getPdf")
     * @param Filesystem $filesystem
     * @return BinaryFileResponse
     */
    public function getPdf(Filesystem $filesystem): BinaryFileResponse
    {
        $pathToPdf = $this->getParameter('app.path_to_pdf');

        if (!$filesystem->exists($pathToPdf)) {
            throw $this->createNotFoundException('Файл не найден');
        }

        $response = new BinaryFileResponse($pathToPdf);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $this->getParameter('app.pdf_filename')
        );

        return $response;
    }
}
