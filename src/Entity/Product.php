<?php

namespace App\Entity;

use App\Enum\ProductStatus;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'error.product.name.not_blank')]
    #[Assert\Length(max: 25, maxMessage: 'error.product.name.length')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    #[Assert\NotNull(message: 'error.product.price.not_null')]
    #[Assert\Type(type: 'numeric', message: 'error.product.price.numeric')]
    #[Assert\Positive(message: 'error.product.price.positive')]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'error.product.description.not_blank')]
    #[Assert\Length(max: 125, maxMessage: 'error.product.description.length')]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'error.product.stock.not_null')]
    #[Assert\Type(type: 'numeric', message: 'error.product.stock.numeric')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'error.product.stock.greater_than_or_equal')]
    private ?int $stock = null;

    #[ORM\Column(enumType: ProductStatus::class)]
    private ?ProductStatus $status = null;

    #[Assert\Callback]
    public function validateStatusAndStock(ExecutionContextInterface $context): void
    {
        if ($this->status === ProductStatus::AVAILABLE && $this->stock <= 0) {
            $context->buildViolation('error.product.status.available')
                ->atPath('status')
                ->addViolation();
        }

        if ($this->status === ProductStatus::SOLD_OUT && $this->stock > 0) {
            $context->buildViolation('error.product.status.sold_out')
                ->atPath('status')
                ->addViolation();
        }
    }

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Assert\Count(min: 1, minMessage: 'error.product.categories.at_least_one')]
    #[ORM\JoinColumn(nullable: false)]
    private Collection $categories;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'product')]
    private Collection $orderItems;

    #[ORM\ManyToOne(targetEntity: Image::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'error.product.image.not_null')]
    private ?Image $image = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getStatus(): ?ProductStatus
    {
        return $this->status;
    }

    public function setStatus(ProductStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
    * @return Collection<int, Category>
    */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function removeAllCategories(): static
    {
        foreach ($this->categories as $category) {
            $this->removeCategory($category);
        }

        return $this;
    }


    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }
}
