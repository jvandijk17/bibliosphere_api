<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Loan;

class BookNotRentedValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($book, Constraint $constraint)
    {
        if (null === $book || '' === $book) {
            return;
        }

        $bookId = $book->getId();

        $existingLoan = $this->em->getRepository(Loan::class)->findOneBy([
            'book' => $bookId,
            'return_date' => null
        ]);

        if ($existingLoan) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ bookId }}', $bookId)
                ->addViolation();
        }
    }
}
