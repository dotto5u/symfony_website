<?php

namespace App\Twig\Components;

use App\Repository\ProductRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProductSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $search = '';

    public function __construct(private ProductRepository $productRepository) {}

    public function getProducts(): array
    {
        return empty($this->search) ? $this->productRepository->getAll() : $this->productRepository->getBySearch($this->search);
    }
}
