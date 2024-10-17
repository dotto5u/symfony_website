<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;

class UserFixtures extends Fixture
{   
    private const USER_REF_PREFIX = 'user_';

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'email' => 'ducret.thomas@yahoo.com',
                'first_name' => 'DUCRET',
                'last_name' => 'Thomas',
                'roles' => ['ROLE_USER'],
                'password' => 'ducret123',
                'orders_ref' => ['order_1', 'order_2', 'order_8'],
                'addresses_ref' => ['address_1', 'address_2']
            ],
            [
                'email' => 'maaroufi.julien@yahoo.com',
                'first_name' => 'MAAROUFI',
                'last_name' => 'Julien',
                'roles' => ['ROLE_USER'],
                'password' => 'maaroufi123',
                'orders_ref' => ['order_3', 'order_9'],
                'addresses_ref' => ['address_3']
            ],
            [
                'email' => 'manick.luc@gmail.com',
                'first_name' => 'MANICK',
                'last_name' => 'Luc',
                'roles' => ['ROLE_USER'],
                'password' => 'manick123',
                'orders_ref' => ['order_4', 'order_5', 'order_10'],
                'addresses_ref' => ['address_4']
            ],
            [
                'email' => 'dotto.matis@gmail.com',
                'first_name' => 'DOTTO',
                'last_name' => 'Matis',
                'roles' => ['ROLE_USER'],
                'password' => 'dotto123',
                'orders_ref' => ['order_6', 'order_7', 'order_11'],
                'addresses_ref' => ['address_5']
            ],
            [
                'email' => 'admin@gmail.com',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'admin123',
                'orders_ref' => [],
                'addresses_ref' => []
            ]
        ];

        foreach ($usersData as $key => $userData)
        {
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
        $user->setPassword($data['password']);

        foreach ($data['orders_ref'] as $orderRef) {
            $user->addOrder($this->getReference($orderRef));
        }

        foreach ($data['addresses_ref'] as $addressRef) {
            $user->addAddress($this->getReference($addressRef));
        }

        return $user;
    }
}
