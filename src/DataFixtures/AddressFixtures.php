<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Address;
use App\DataFixtures\UserFixtures;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{   
    private const ADDRESS_REF_PREFIX = 'address_';

    public function load(ObjectManager $manager): void
    {
        $addressesData = [
           
        ];

        foreach ($addressesData as $key => $addressData)
        {
            $address = $this->createAddress($addressData);
            $manager->persist($address);
            $this->addReference(self::ADDRESS_REF_PREFIX.($key + 1), $address);
        }

        $manager->flush();
    }

    private function createAddress(array $data): Addrss 
    {
        $address = new Address();

        
        return $address;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
