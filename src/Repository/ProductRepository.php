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
    private ValidatorInterface $validator;
    private ManagerRegistry $registry;
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->registry = $registry;
        parent::__construct($registry, Product::class);
    }

    public function storeProduct($body): Product
    {
        $product = new Product();
        $this->setProduct($body, $product);

        $errors = $this->validator->validate($product);

        // Throw ValidationException if validation fails
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->registry->getManager()->persist($product);
        $this->registry->getManager()->flush();

        return $product;
    }

    public function paginate(mixed $page, mixed $limit)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    public function updateProduct($id, $body): Product
    {
        $product = $this->find($id);

        $this->setProduct($body, $product);

        $errors = $this->validator->validate($product);

        // Throw ValidatorException if validation fails
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->registry->getManager()->persist($product);
        $this->registry->getManager()->flush();

        return $product;
    }

    public function deleteProduct($id)
    {
        $this->registry->getManager()->remove($this->find($id));
        $this->registry->getManager()->flush();
    }

    private function setProduct($body, Product $product): void
    {
        if (isset($body['name'])) {
            $product->setName($body['name']);
        }

        if (isset($body['description'])) {
            $product->setDescription($body['description']);
        }

        if (isset($body['price'])) {
            $product->setPrice($body['price']);
        }

        if (isset($body['expiry_date'])) {
            $product->setExpiryDate(new \DateTime($body['expiry_date']));
        }
        if (isset($body['date_of_manufacture'])) {
            $product->setDateOfManufacture(new \DateTime($body['date_of_manufacture']));
        }
    }
}
