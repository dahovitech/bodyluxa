<?php

namespace App\Service;

use App\Entity\Config;
use App\Entity\Language;
use App\Repository\ConfigRepository;
use App\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class ConfigService
{
    private ?Language $currentLanguage = null;

    public function __construct(
        private ConfigRepository $configRepository,
        private LanguageRepository $languageRepository,
        private RequestStack $requestStack
    ) {
    }

    public function get(string $key, ?Language $language = null): ?string
    {
        $config = $this->configRepository->findByKey($key);
        if (!$config) {
            return null;
        }

        $language = $language ?: $this->getCurrentLanguage();
        return $config->getTranslatedValue($language);
    }

    public function getConfig(string $key): ?Config
    {
        return $this->configRepository->findByKey($key);
    }

    public function getAllConfigs(): array
    {
        return $this->configRepository->findActive();
    }

    public function getConfigsByType(string $type): array
    {
        return $this->configRepository->findByType($type);
    }

    private function getCurrentLanguage(): Language
    {
        if ($this->currentLanguage === null) {
            $request = $this->requestStack->getCurrentRequest();
            $locale = $request ? $request->getLocale() : 'fr';
            
            $this->currentLanguage = $this->languageRepository->findOneBy(['code' => $locale, 'isActive' => true]) 
                ?: $this->languageRepository->findOneBy(['isDefault' => true])
                ?: $this->languageRepository->findOneBy(['code' => 'fr']);
        }

        return $this->currentLanguage;
    }

    public function getWebsiteTitle(?Language $language = null): string
    {
        return $this->get('website_title', $language) ?? 'BodyLuxa';
    }

    public function getWebsiteDescription(?Language $language = null): string
    {
        return $this->get('website_description', $language) ?? 'Votre partenaire pour le tourisme médical et esthétique';
    }

    public function getContactEmail(): string
    {
        return $this->get('contact_email') ?? 'contact@bodyluxa.com';
    }

    public function getContactPhone(): string
    {
        return $this->get('contact_phone') ?? '+33 1 23 45 67 89';
    }

    public function getCompanyAddress(?Language $language = null): string
    {
        return $this->get('company_address', $language) ?? 'Paris, France';
    }

    public function getWhatsappNumber(): string
    {
        return $this->get('whatsapp_number') ?? '+33123456789';
    }
}
