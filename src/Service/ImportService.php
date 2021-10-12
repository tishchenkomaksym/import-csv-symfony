<?php

namespace App\Service;


use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validation;


class ImportService
{

    private EntityManagerInterface $entityManager;

    public array $error = [];
    private ProductsRepository $repository;

    public function __construct(EntityManagerInterface $em, ProductsRepository $repository)
    {
        $this->entityManager = $em;
        $this->repository = $repository;
    }

    public function importCsv($path)
    {
        $csv = Reader::createFromPath('stock.csv', 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset
        $csv->getHeader();
//get 25 records starting from the 11th row
        $stmt = Statement::create();

        $records = $stmt->process($csv);
        foreach ($records as $key => $record) {
            $this->validateProduct([
                'name' => $record['Product Name'],
                'code' => $record['Product Code'],
                'description' => $record['Product Description'],
                'stock' => $record['Stock'],
                'price' => $record['Cost in GBP'],
                'discontinued' => $record['Discontinued']
                ], $key);

        }

        echo $path;
    }

    public function saveProduct($record, $key)
    {
        $record = $this->filterRules($record, $key);

        if (empty($record)){
            return;
        }

        $product = new Products();
        $product->setName($record['name'])
                ->setCode($record['code'])
                ->setDescription($record['description'])
                ->setPrice($record['price'])
                ->setStock($record['stock'])
                ->setDiscontinuedAt($record['discontinued'] == '' ? null : $record['discontinued']);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    private function filterRules($record, $key)
    {
        foreach ($record as $i => $item) {
            str_replace("\r", "\n", str_replace("\r\n", "\n", $record[$i]));
        }

        if ($record['discontinued'] == 'yes'){
            $record['discontinued'] = (new \DateTimeImmutable());
            $filteredRow =  $record;
        } elseif ( $record['price'] >= 5 && $record['stock'] >= 10 &&  $record['price'] <= 1000){
            $filteredRow =  $record;
        } else {
            $this->error[] = 'The ' . $key . ' did not match import rules';
        }

        return $filteredRow ?? [];
    }

    public function validateProduct($product, $key)
    {
        $validator = Validation::createValidator();

        $constraint = new Collection([
                    'name' => [new NotBlank(),  new Length(['max' => 50])],
                    'code' => [new NotBlank(),  new Length(['max' => 10])],
                    'description' => [new NotBlank(), new Length(['max' => 255])],
                    'stock' => [new Type('numeric')],
                    'price' => [ new Type('numeric')],
                    'discontinued' => new Length(['max' => 5]),
            ]);

        $violations = $validator->validate($product, $constraint);
        if ($violations->count() > 0){
            $this->error[] = 'The ' . $key . ' ' . $violations->__toString();
        }

        $unique = $this->checkUnique($product['code']);
        if (!empty($unique)){
            $this->error[] = 'The line ' . $key . ' with code' . $product['code'] . ' already in db';
        }

        if ($violations->count() == 0 && empty($unique)){
            $this->saveProduct($product, $key);
        }

    }

    public function checkUnique($code): ?Products
    {
        return $this->repository->findOneBy(['code' => $code]);
    }

}