<?php

namespace App\Service;

use App\Entity\Responsable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ResponsableService
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
     * @return Responsable|null
     */
    public function get(int $id): ?Responsable
    {
        return $this->getRepository()->find($id);
    }

    /**
     *
     * @return Responsable|null
     */
    public function getResponsableByEmail($critere): ?Responsable
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
    public function getResponsable(Array $params = array(), int $limit = null, $offset = null): ?array
    {
        return $this->getRepository()->findBy($params, null, $limit, $offset);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Responsable::class);
    }

    /**
     * @param Responsable $Responsable
     * @return Responsable
     * @throws \Error
     */
    public function persist(Responsable $Responsable): Responsable
    {
        try {
            $this->entityManager->persist($Responsable);
            $this->entityManager->flush();
        } catch (\Error $error) {
            throw new \Error("Bad Request");
        }

        return $Responsable;
    }

    /**
     * @param Responsable $Responsable
     * @return bool
     * @throws \Error
     */
    public function remove(Responsable $Responsable): bool
    {
        $this->entityManager->remove($Responsable);
        $this->entityManager->flush();

        return true;
    }

    public function save(Responsable $Responsable): Responsable
    {
        $this->entityManager->persist($Responsable);
        $this->entityManager->flush();

        return $Responsable;
    }

    public function toJson($Responsable)
    {
        
        $jsonResponsable['email'] = $Responsable->getEmail();
        $jsonResponsable['roles'] = $Responsable->getRoles();
        $jsonResponsable['password'] = $Responsable->getPassword();
        $jsonResponsable['cin'] = $Responsable->getCin();
        $jsonResponsable['phone'] = $Responsable->getPhone();
        
       
        return $jsonResponsable;
    }

}
