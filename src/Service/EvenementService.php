<?php

namespace App\Service;

use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class EvenementService
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
     * @return Evenement|null
     */
    public function get(int $id): ?Evenement
    {
        return $this->getRepository()->find($id);
    }

    /**
     *
     * @return Evenement|null
     */
    public function getEvenementByEmail($critere): ?Evenement
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
    public function getEvenement(Array $params = array(), int $limit = null, $offset = null): ?array
    {
        return $this->getRepository()->findBy($params, null, $limit, $offset);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Evenement::class);
    }

    /**
     * @param Evenement $Evenement
     * @return Evenement
     * @throws \Error
     */
    public function persist(Evenement $Evenement): Evenement
    {
        try {
            $this->entityManager->persist($Evenement);
            $this->entityManager->flush();
        } catch (\Error $error) {
            throw new \Error("Bad Request");
        }

        return $Evenement;
    }

    /**
     * @param Evenement $Evenement
     * @return bool
     * @throws \Error
     */
    public function remove(Evenement $Evenement): bool
    {
        $this->entityManager->remove($Evenement);
        $this->entityManager->flush();

        return true;
    }

    public function save(Evenement $Evenement): Evenement
    {
        $this->entityManager->persist($Evenement);
        $this->entityManager->flush();

        return $Evenement;
    }

    public function toJson($Evenement)
    {
        $jsonEvenement['id'] = $Evenement->getId();
        $jsonEvenement['title'] = $Evenement->getTitle();
        $jsonEvenement['description'] = $Evenement->getDescription();
        $jsonEvenement['start_date'] = $Evenement->getStartDate();
        $jsonEvenement['end_date'] = $Evenement->getEndDate();
        $jsonEvenement['statut'] = $Evenement->getStatut();
        
       
        return $jsonEvenement;
    }

}
