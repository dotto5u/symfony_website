<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;
use App\DataFixtures\OrderFixtures;
use App\DataFixtures\AddressFixtures;

class UserFixtures extends Fixture implements DependentFixtureInterface
{   
    private const USER_REF_PREFIX = 'user_';

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'email' => 'ducret.thomas@yahoo.com',
                'firsName' => 'DUCRET',
                'lastName' => 'Thomas',
                'roles' => ['ROLE_USER'],
                'password' => 'ducret123',
                'orders' => ['order_1', 'order_2', 'order_8'],
                'addresses' => ['address_1', 'address_2']
            ],
            [
                'email' => 'maaroufi.julien@yahoo.com',
                'firsName' => 'MAAROUFI',
                'lastName' => 'Julien',
                'roles' => ['ROLE_USER'],
                'password' => 'maaroufi123',
                'orders' => ['order_3', 'order_9'],
                'addresses' => ['address_3']
            ],
            [
                'email' => 'manick.luc@gmail.com',
                'firsName' => 'MANICK',
                'lastName' => 'Luc',
                'roles' => ['ROLE_USER'],
                'password' => 'manick123',
                'orders' => ['order_4', 'order_5', 'order_10'],
                'addresses' => ['address_4']
            ],
            [
                'email' => 'dotto.matis@gmail.com',
                'firsName' => 'DOTTO',
                'lastName' => 'Matis',
                'roles' => ['ROLE_USER'],
                'password' => 'dotto123',
                'orders' => ['order_6', 'order_7', 'order_11'],
                'addresses' => ['address_5']
            ],
            [
                'email' => 'admin@gmail.com',
                'firsName' => 'admin',
                'lastName' => 'admin',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'admin123',
                'orders' => [],
                'addresses' => []
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
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setRoles($data['roles']);
        $user->setPassword($data['password']);

        foreach ($data['orders'] as $key => $order) {
            $user->addOrder($order);
        }

        foreach ($data['addresses'] as $key => $address) {
            $user->addAddress($address);
        }

        return $user;
    }

    public function getDependencies()
    {
        return [
            OrderFixtures::class,
            AddressFixtures::class
        ];
    }
}
