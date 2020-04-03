<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Entity\Project;
use App\Validator\ProjectValidator;
use Doctrine\ORM\EntityManagerInterface;

class ProjectManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager,ProjectValidator $validator)
    {
        parent::__construct($manager,$validator);
    }

    public function initialise(EntityInterface $entity): void
    {

        if (null === $entity->getCreatedAt()) {
            $entity->setCreatedAt(new \DateTime());
            $entity->setEnable(true);
        } else {
            $entity->setModifiedAt(new \DateTime());
        }
    }
}
