<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService 
{
        public function __construct(private EntityManagerInterface $em, private UserPasswordHasherInterface $hasher) {}
        public function save(User $user): User
        {
            $password = $this->hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        }
}
