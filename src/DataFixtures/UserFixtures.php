<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private const USER_REF_PREFIX = 'user_';
    private $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'email' => 'ducret.thomas@yahoo.com',
                'first_name' => 'DUCRET',
                'last_name' => 'Thomas',
                'roles' => ['ROLE_USER'],
                'password' => 'ducret123'
            ],
            [
                'email' => 'maaroufi.julien@yahoo.com',
                'first_name' => 'MAAROUFI',
                'last_name' => 'Julien',
                'roles' => ['ROLE_USER'],
                'password' => 'maaroufi123'
            ],
            [
                'email' => 'manick.luc@gmail.com',
                'first_name' => 'MANICK',
                'last_name' => 'Luc',
                'roles' => ['ROLE_USER'],
                'password' => 'manick123'
            ],
            [
                'email' => 'dotto.matis@gmail.com',
                'first_name' => 'DOTTO',
                'last_name' => 'Matis',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'dotto123'
            ]
        ];

        foreach ($usersData as $key => $userData) {
            $user = $this->createUser($userData);
            $manager->persist($user);
            $this->addReference(self::USER_REF_PREFIX.($key + 1), $user);
        }

        $manager->flush();
    }

    private function createUser(array $data): User
    {
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);
        $user->setRoles($data['roles']);

        $hashedPassword = $this->userPasswordHasherInterface->hashPassword(
            $user,
            $data['password']
        );
        $user->setPassword($hashedPassword);

        return $user;
    }
}
