<?php

namespace App\Service;

use App\Entity\Reunion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ReunionService
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
     * @return Reunion|null
     */
    public function get(int $id): ?Reunion
    {
        return $this->getRepository()->find($id);
    }

    /**
     *
     * @return Reunion|null
     */
    public function getReunionByEmail($critere): ?Reunion
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
    public function getReunion(Array $params = array(), int $limit = null, $offset = null): ?array
    {
        return $this->getRepository()->findBy($params, null, $limit, $offset);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Reunion::class);
    }

    /**
     * @param Reunion $Reunion
     * @return Reunion
     * @throws \Error
     */
    public function persist(Reunion $Reunion): Reunion
    {
        try {
            $this->entityManager->persist($Reunion);
            $this->entityManager->flush();
        } catch (\Error $error) {
            throw new \Error("Bad Request");
        }

        return $Reunion;
    }

    /**
     * @param Reunion $Reunion
     * @return bool
     * @throws \Error
     */
    public function remove(Reunion $Reunion): bool
    {
        $this->entityManager->remove($Reunion);
        $this->entityManager->flush();

        return true;
    }

    public function save(Reunion $Reunion): Reunion
    {
        $this->entityManager->persist($Reunion);
        $this->entityManager->flush();

        return $Reunion;
    }

    public function toJson($Reunion)
    {
        $jsonReunion['id'] = $Reunion->getId();
        $jsonReunion['title'] = $Reunion->getTitle();
        $jsonReunion['start_date'] = $Reunion->getStartDate();
        $jsonReunion['end_date'] = $Reunion->getEndDate();
        $jsonReunion['description'] = $Reunion->getDescription();
        $jsonReunion['piece_jointe'] = $Reunion->getPieceJointe();
        $jsonReunion['url'] = $Reunion->getUrl();
        $jsonReunion['evenement'] = $Reunion->getEvenement();
       
        return $jsonReunion;
    }

}
