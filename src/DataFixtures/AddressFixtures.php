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
           [
                'street' => '20 Rue de la Graisse',
                'postal_code' => '57000',
                'city' => 'Metz',
                'country' => 'France',
                'user_ref' => 'user_1'
           ],
           [
                'street' => '15 Avenue de la Liberté',
                'postal_code' => '1931',
                'city' => 'Luxembourg',
                'country' => 'Luxembourg',
                'user_ref' => 'user_1'
           ],
           [
                'street' => '10 Rue des Hibiscus',
                'postal_code' => '97190',
                'city' => 'Le Gosier',
                'country' => 'Guadeloupe',
                'user_ref' => 'user_2'
           ],
           [
                'street' => '5 Rue de la Ferme',
                'postal_code' => '57680',
                'city' => 'Gorze',
                'country' => 'France',
                'user_ref' => 'user_3'
           ],
           [
                'street' => '112 Bcle des Roseaux',
                'postal_code' => '57100',
                'city' => 'Thionville',
                'country' => 'France',
                'user_ref' => 'user_4'
           ],
        ];

        foreach ($addressesData as $key => $addressData)
        {
            $address = $this->createAddress($addressData);
            $manager->persist($address);
            $this->addReference(self::ADDRESS_REF_PREFIX.($key + 1), $address);
        }

        $manager->flush();
    }

    private function createAddress(array $data): Address 
    {
        $address = new Address();
        $address->setStreet($data['street']);
        $address->setPostalCode($data['postal_code']);
        $address->setCity($data['city']);
        $address->setCountry($data['country']);
        $address->setUser($this->getReference($data['user_ref']));
        
        return $address;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
