<?php

namespace App\Command;

use App\Entity\Subdivision;
use Doctrine\ORM\EntityManagerInterface;
use Mpdf\Mpdf;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MakePdfCommand extends Command
{
    protected static $defaultName = 'app:make-pdf';

    /** @var ContainerInterface */
    private $container;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Filesystem */
    private $filesystem;

    /**
     * MakePdfCommand constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $em
     * @param Filesystem $filesystem
     */
    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $em,
        Filesystem $filesystem
    ) {
        parent::__construct();

        $this->container  = $container;
        $this->em         = $em;
        $this->filesystem = $filesystem;
    }

    protected function configure()
    {
        $this
            ->setDescription('Генерирует PDF-файл с телефонной книгой')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \Mpdf\MpdfException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io               = new SymfonyStyle($input, $output);
        $cache            = new FilesystemAdapter();
        $needUpdatePdfKey = $this->container->getParameter('app.need_update_pdf_key');
        $pathFile         = $this->container->getParameter('app.path_to_pdf');

        /** @var ItemInterface $needUpdate */
        $needUpdate = $cache->getItem($needUpdatePdfKey);

        /** Проверяем есть ли необходимость в обновлении файла (в кэше есть нужный ключ или файл отсутствует) */
        if ($this->filesystem->exists($pathFile) && !$needUpdate->get()) {
            $io->success('Обновление файла не требуется.');

            return 0;
        }

        $subdivisions = $this->em->getRepository(Subdivision::class)->findAllWithPhones();

        if (!$subdivisions) {
            $io->error($this->container->get('translator')->trans('exception.subdivisions_not_found'));

            return 1;
        }

        $html = $this->container->get('twig')->render('make_pdf/pdf_list.html.twig', ['subdivisions' => $subdivisions]);

        $mpdf = new Mpdf(['margin_left' => 5, 'margin_right' => 5, 'margin_header' => 5, 'margin_footer' => 5]);
        $mpdf->use_kwt = true;
        $mpdf->WriteHTML($html);

        $pdfString = $mpdf->Output(null, 'S');

        try {
            $this->filesystem->dumpFile($pathFile, $pdfString);
        } catch (IOException $e) {
            $io->error($e->getMessage());

            return 1;
        }

        $needUpdate->set(0);
        $cache->save($needUpdate);

        $io->success('Сгенерирован новый PDF-файл.');

        return 0;
    }
}
