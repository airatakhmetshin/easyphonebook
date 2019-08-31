<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MakePdfSubscriber implements EventSubscriber
{
    /** @var string */
    private $needUpdatePdfKey;

    /**
     * MakePdfSubscriber constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->needUpdatePdfKey = $container->getParameter('app.need_update_pdf_key');
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->index();
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index();
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->index();
    }

    public function index()
    {
        $cache = new FilesystemAdapter();

        /** @var ItemInterface $needUpdatePdf */
        $needUpdatePdf = $cache->getItem($this->needUpdatePdfKey);

        $needUpdatePdf->set(true);
        $cache->save($needUpdatePdf);
    }
}
