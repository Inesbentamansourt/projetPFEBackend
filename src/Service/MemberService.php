<?php

namespace App\Service;

use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class MemberService
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
     * @return Member|null
     */
    public function get(int $id): ?Member
    {
        return $this->getRepository()->find($id);
    }

    /**
     *
     * @return Member|null
     */
    public function getMemberByEmail($critere): ?Member
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
    public function getMember(Array $params = array(), int $limit = null, $offset = null): ?array
    {
        return $this->getRepository()->findBy($params, null, $limit, $offset);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Member::class);
    }

    /**
     * @param Member $Member
     * @return Member
     * @throws \Error
     */
    public function persist(Member $Member): Member
    {
        try {
            $this->entityManager->persist($Member);
            $this->entityManager->flush();
        } catch (\Error $error) {
            throw new \Error("Bad Request");
        }

        return $Member;
    }

    /**
     * @param Member $Member
     * @return bool
     * @throws \Error
     */
    public function remove(Member $Member): bool
    {
        $this->entityManager->remove($Member);
        $this->entityManager->flush();

        return true;
    }

    public function save(Member $Member): Member
    {
        $this->entityManager->persist($Member);
        $this->entityManager->flush();

        return $Member;
    }

    public function toJson($Member)
    {
        $jsonMember['id'] = $Member->getId();
        $jsonMember['email'] = $Member->getEmail();
        $jsonMember['roles'] = $Member->getRoles();
        $jsonMember['password'] = $Member->getPassword();
        $jsonMember['cin'] = $Member->getCin();
        $jsonMember['phone'] = $Member->getPhone();
        
       
        return $jsonMember;
    }

}
