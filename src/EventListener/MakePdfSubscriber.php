<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class MakePdfSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        ];
    }

    public function postPersist($needUpdatePdfKey)
    {
        $this->index($needUpdatePdfKey);
    }

    public function postUpdate($needUpdatePdfKey)
    {
        $this->index($needUpdatePdfKey);
    }

    public function postRemove($needUpdatePdfKey)
    {
        $this->index($needUpdatePdfKey);
    }

    public function index($needUpdatePdfKey)
    {
        $cache = new FilesystemAdapter();

        /** @var ItemInterface $needUpdatePdf */
        $needUpdatePdf = $cache->getItem($needUpdatePdfKey);

        $needUpdatePdf->set(true);
        $cache->save($needUpdatePdf);
    }
}
