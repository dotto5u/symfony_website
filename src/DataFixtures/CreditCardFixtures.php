<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\Entity\CreditCard;

class CreditCardFixtures extends Fixture implements DependentFixtureInterface
{
    private const CREDID_CARD_REF_PREFIX = 'credit_card_';

    public function load(ObjectManager $manager): void
    {
        $creditCardsData = [
            [
                'number' => '4111111111111111',
                'expiration_date' => new \DateTime('2026-12-01'),
                'cvv' => '123',
                'user_ref' => 'user_4',
            ],
        ];

        foreach ($creditCardsData as $key => $creditCardData) {
            $creditCard = $this->createCreditCard($creditCardData);
            $manager->persist($creditCard);
            $this->addReference(self::CREDID_CARD_REF_PREFIX.($key + 1), $creditCard);
        }

        $manager->flush();
    }

    private function createCreditCard(array $data): CreditCard
    {
        $creditCard = new CreditCard();
        $creditCard->setNumber($data['number']);
        $creditCard->setExpirationDate($data['expiration_date']);
        $creditCard->setCvv($data['cvv']);
        $creditCard->addUser($this->getReference($data['user_ref']));

        return $creditCard;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
