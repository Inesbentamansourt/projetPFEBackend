<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CategoryService
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
     * @return Category|null
     */
    public function get(int $id): ?Category
    {
        return $this->getRepository()->find($id);
    }

    /**
     *
     * @return Category|null
     */
    public function getCategoryByEmail($critere): ?Category
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
    public function getCategory(Array $params = array(), int $limit = null, $offset = null): ?array
    {
        return $this->getRepository()->findBy($params, null, $limit, $offset);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Category::class);
    }

    /**
     * @param Category $Category
     * @return Category
     * @throws \Error
     */
    public function persist(Category $Category): Category
    {
        try {
            $this->entityManager->persist($Category);
            $this->entityManager->flush();
        } catch (\Error $error) {
            throw new \Error("Bad Request");
        }

        return $Category;
    }

    /**
     * @param Category $Category
     * @return bool
     * @throws \Error
     */
    public function remove(Category $Category): bool
    {
        $this->entityManager->remove($Category);
        $this->entityManager->flush();

        return true;
    }

    public function save(Category $Category): Category
    {
        $this->entityManager->persist($Category);
        $this->entityManager->flush();

        return $Category;
    }

    public function toJson($Category)
    {
        $jsonCategory['id'] = $Category->getId();
        $jsonCategory['title'] = $Category->getTitle();
       
   
       
        return $jsonCategory;
    }

}
