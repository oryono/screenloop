<?php

namespace App\Repository;

use AllowDynamicProperties;
use App\Entity\Product;
use App\Exception\ValidationException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 */
#[AllowDynamicProperties] class ProductRepository extends ServiceEntityRepository
{
    private $validator;
    private $registry;
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->registry = $registry;
        parent::__construct($registry, Product::class);
    }

    public function storeProduct($body)
    {
        $product = new Product();
        $product->setName($body['name']);
        $product->setDescription($body['description']);

        $errors = $this->validator->validate($product);

        // Throw ValidatorException if validation fails
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->registry->getManager()->persist($product);
        $this->registry->getManager()->flush();

        return $product;
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
