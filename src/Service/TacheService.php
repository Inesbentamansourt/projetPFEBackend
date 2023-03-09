<?php

namespace App\Service;

use App\Entity\Tache;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class TacheService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ValidatorInterface */
    private $validator;
  

    /**
     * AddressesService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
      
    }

    /**
     * @param int $id
     *
     * @return Tache|null
     */
    public function get(int $id): ?Tache
    {
        return $this->getRepository()->find($id);
    }

    /**
     *
     * @return Tache|null
     */
    public function getTacheByEmail($critere): ?Tache
    {
        return $this->getRepository()->findOneBy($critere);
    }

    /**
     * @param array $params
     * @param int|null $limit
     * @param null $offset
     *
     * @return array|null
     */
    public function getTache(Array $params = array(), int $limit = null, $offset = null): ?array
    {
        return $this->getRepository()->findBy($params, null, $limit, $offset);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Tache::class);
    }

    /**
     * @param Tache $Tache
     * @return Tache
     * @throws \Error
     */
    public function persist(Tache $Tache): Tache
    {
        try {
            $this->entityManager->persist($Tache);
            $this->entityManager->flush();
        } catch (\Error $error) {
            throw new \Error("Bad Request");
        }

        return $Tache;
    }

    /**
     * @param Tache $Tache
     * @return bool
     * @throws \Error
     */
    public function remove(Tache $Tache): bool
    {
        $this->entityManager->remove($Tache);
        $this->entityManager->flush();

        return true;
    }

    public function save(Tache $Tache): Tache
    {
        $this->entityManager->persist($Tache);
        $this->entityManager->flush();

        return $Tache;
    }

    public function toJson($Tache)
    {
        $jsonTache['id'] = $Tache->getId();
        $jsonTache['title'] = $Tache->getTitle();
        $jsonTache['description'] = $Tache->getDescription();
        $jsonTache['estimate_start_date'] = $Tache->getEstimateStartDate();
        $jsonTache['estimate_end_date'] = $Tache->getEstimateEndDate();
        $jsonTache['achivement_start_date'] = $Tache->getAchivementStartDate();
        $jsonTache['achivement_end_date'] = $Tache->getAchivementEndDate();
        $jsonTache['evenement'] = $Tache->getEvenement();
       
        return $jsonTache;
    }

}
