<?php

namespace App\Twig\Components;

use App\Entity\CreditCard;
use App\Repository\CreditCardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;

#[AsLiveComponent]
final class CreditCardPage extends AbstractController
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public bool $showCreditCardForm;

    #[LiveProp]
    public int $userId;

    #[LiveProp]
    public array $creditCards;

    #[LiveProp(writable: true)]
    #[NotBlank(message: "error.credit_card.number.not_blank")]
    #[Type(type: "numeric", message: "error.credit_card.number.numeric")]
    #[Length(min: 16, max: 16, exactMessage: "error.credit_card.number.length")]
    public string $number = '';

    #[LiveProp(writable: true)]
    #[NotBlank(message: "error.credit_card.expiration_date.not_null")]
    public string $expirationDate = '';

    #[LiveProp(writable: true)]
    #[NotBlank(message: "error.credit_card.cvv.not_blank")]
    #[Type(type: "numeric", message: "error.credit_card.cvv.numeric")]
    #[Length(min: 3, max: 3, exactMessage: "error.credit_card.cvv.length")]
    public string $cvv = '';

    public function __construct(private CreditCardRepository $creditCardRepository)
    {
    }

    public function mount(): void
    {
        $this->showCreditCardForm = false;
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->number = '';
        $this->expirationDate = '';
        $this->cvv = '';
    }

    private function updateCreditCards(): void
    {
        $creditCards = $this->creditCardRepository->getByUser($this->userId);

        foreach ($creditCards as $creditCard) {
            $creditCardId = $creditCard->getId();

            $creditCardHydrated['id'] = $creditCardId;
            $creditCardHydrated['number'] = $creditCard->getNumber();
            $creditCardHydrated['expirationDate'] = $creditCard->getExpirationDate()->format('d/m/y');
            $creditCardHydrated['cvv'] = $creditCard->getCvv();

            $this->creditCards[$creditCardId] = $creditCardHydrated;
        }
    }

    #[LiveAction]
    public function toggleCreditCardForm(): void
    {
        $this->resetForm();
        $this->showCreditCardForm = !$this->showCreditCardForm;
    }

    #[LiveAction]
    public function saveCreditCard(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->validate();

        $user = $userRepository->getById($this->userId);

        $dateTime = null;

        try {
            $dateTime = new \DateTime($this->expirationDate);
        } catch (\Exception) {
            $dateTime = new \DateTime();
        }

        $creditCard = new CreditCard();
        $creditCard->setNumber($this->number);
        $creditCard->setExpirationDate($dateTime);
        $creditCard->setCvv($this->cvv);
        $creditCard->addUser($user);

        $entityManager->persist($creditCard);
        $entityManager->flush();

        $this->updateCreditCards();
        $this->toggleCreditCardForm();
    }
}
