<?php

namespace Knp\DoctrineBehaviors\ORM\Translatable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events;

class TranslatableListener implements EventSubscriber
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        if (in_array('Knp\DoctrineBehaviors\ORM\Translation\Translatable', $classMetadata->reflClass->getTraitNames())) {
            $classMetadata->mapOneToMany([
                'fieldName'    => 'translations',
                'mappedBy'     => 'translatable',
                'targetEntity' => $classMetadata->name.'Translation'
            ]);
        }
        if (in_array('Knp\DoctrineBehaviors\ORM\Translation\Translation', $classMetadata->reflClass->getTraitNames())) {
            $classMetadata->mapManyToOne([
                'fieldName'    => 'translatable',
                'inversedBy'   => 'translations',
                // remove "Translation" suffix:
                'targetEntity' => substr($classMetadata->name, 0, -11)
            ]);
        }
    }

    public function getSubscribedEvents()
    {
        return array(Events::loadClassMetadata);
    }
}