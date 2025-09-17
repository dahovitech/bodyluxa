<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
#[ORM\Table(name: 'config')]
class Config
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Assert\NotBlank]
    private string $configKey;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $configValue = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $configType = 'text'; // text, textarea, image, url, email, phone

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'integer')]
    private int $sortOrder = 0;

    #[ORM\OneToMany(mappedBy: 'config', targetEntity: ConfigTranslation::class, cascade: ['persist', 'remove'])]
    private Collection $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfigKey(): string
    {
        return $this->configKey;
    }

    public function setConfigKey(string $configKey): static
    {
        $this->configKey = $configKey;
        return $this;
    }

    public function getConfigValue(): ?string
    {
        return $this->configValue;
    }

    public function setConfigValue(?string $configValue): static
    {
        $this->configValue = $configValue;
        return $this;
    }

    public function getConfigType(): string
    {
        return $this->configType;
    }

    public function setConfigType(string $configType): static
    {
        $this->configType = $configType;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * @return Collection<int, ConfigTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ConfigTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setConfig($this);
        }

        return $this;
    }

    public function removeTranslation(ConfigTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            if ($translation->getConfig() === $this) {
                $translation->setConfig(null);
            }
        }

        return $this;
    }

    public function getTranslation(Language $language): ?ConfigTranslation
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLanguage() === $language) {
                return $translation;
            }
        }
        return null;
    }

    public function getTranslatedValue(Language $language): ?string
    {
        $translation = $this->getTranslation($language);
        return $translation ? $translation->getValue() : $this->configValue;
    }

    public function __toString(): string
    {
        return $this->configKey;
    }
}
