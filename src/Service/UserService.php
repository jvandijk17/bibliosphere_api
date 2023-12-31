<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Library;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->passwordHasher = $passwordHasher;
    }


    public function saveUser(?User $user, array $data): User
    {
        if (!$user) {
            $user = new User();
            $user->setRegistrationDate(new \DateTime());
            $this->entityManager->persist($user);
        }

        if (isset($data['first_name'])) {
            $user->setFirstName($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $user->setLastName($data['last_name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['password'])) {
            $plainPassword = $data['password'];
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }
        if (isset($data['address'])) {
            $user->setAddress($data['address']);
        }
        if (isset($data['city'])) {
            $user->setCity($data['city']);
        }
        if (isset($data['province'])) {
            $user->setProvince($data['province']);
        }
        if (isset($data['postal_code'])) {
            $user->setPostalCode($data['postal_code']);
        }
        if (isset($data['birth_date'])) {
            $user->setBirthDate(new \DateTime($data['birth_date']));
        }
        if (isset($data['reputation'])) {
            $user->setReputation($data['reputation']);
        }
        if (isset($data['blocked'])) {
            $user->setBlocked($data['blocked']);
        }
        if (isset($data['roles'])) {
            $user->setRoles($data['roles']);
        }
        if (isset($data['library'])) {
            $user->setLibrary($this->entityManager->getRepository(Library::class)->find($data['library']));
        }

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException(json_encode(array_map(function ($error) {
                return $error->getMessage();
            }, iterator_to_array($errors))));
        }

        $this->entityManager->flush();

        return $user;
    }
}
