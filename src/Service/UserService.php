<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserService
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
     * @return User|null
     */
    public function get(int $id): ?User
    {
        return $this->getRepository()->find($id);
    }

    /**
     *
     * @return User|null
     */
    public function getUserByEmail($critere): ?User
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
    public function getUser(Array $params = array(), int $limit = null, $offset = null): ?array
    {
        return $this->getRepository()->findBy($params, null, $limit, $offset);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(User::class);
    }

    /**
     * @param User $User
     * @return User
     * @throws \Error
     */
    public function persist(User $User): User
    {
        try {
            $this->entityManager->persist($User);
            $this->entityManager->flush();
        } catch (\Error $error) {
            throw new \Error("Bad Request");
        }

        return $User;
    }

    /**
     * @param User $User
     * @return bool
     * @throws \Error
     */
    public function remove(User $User): bool
    {
        $this->entityManager->remove($User);
        $this->entityManager->flush();

        return true;
    }

    public function save(User $User): User
    {
        $this->entityManager->persist($User);
        $this->entityManager->flush();

        return $User;
    }

    public function toJson($User)
    {
        $jsonUser['id'] = $User->getId();
        $jsonUser['email'] = $User->getEmail();
        $jsonUser['password'] = $User->getPassword();
     
       
        return $jsonUser;
    }

}
