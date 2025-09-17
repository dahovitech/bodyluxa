<?php

namespace App\DataFixtures;

use App\Entity\Config;
use App\Entity\ConfigTranslation;
use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $languages = $manager->getRepository(Language::class)->findAll();
        $frLanguage = null;
        $enLanguage = null;
        $deLanguage = null;
        $trLanguage = null;

        foreach ($languages as $language) {
            switch ($language->getCode()) {
                case 'fr':
                    $frLanguage = $language;
                    break;
                case 'en':
                    $enLanguage = $language;
                    break;
                case 'de':
                    $deLanguage = $language;
                    break;
                case 'tr':
                    $trLanguage = $language;
                    break;
            }
        }

        $configs = [
            [
                'key' => 'website_title',
                'type' => 'text',
                'description' => 'Titre du site web',
                'values' => [
                    'fr' => 'BodyLuxa - Expert en Tourisme Médical',
                    'en' => 'BodyLuxa - Medical Tourism Expert',
                    'de' => 'BodyLuxa - Medizintourismus-Experte',
                    'tr' => 'BodyLuxa - Medikal Turizm Uzmanı'
                ]
            ],
            [
                'key' => 'website_description',
                'type' => 'textarea',
                'description' => 'Description du site web',
                'values' => [
                    'fr' => 'Votre partenaire de confiance pour des soins médicaux et esthétiques de qualité en Turquie.',
                    'en' => 'Your trusted partner for quality medical and aesthetic care in Turkey.',
                    'de' => 'Ihr vertrauenswürdiger Partner für qualitativ hochwertige medizinische und ästhetische Behandlungen in der Türkei.',
                    'tr' => 'Türkiye\'de kaliteli tıbbi ve estetik bakım için güvenilir ortağınız.'
                ]
            ],
            [
                'key' => 'contact_email',
                'type' => 'email',
                'description' => 'Email de contact principal',
                'values' => [
                    'fr' => 'contact@bodyluxa.com',
                    'en' => 'contact@bodyluxa.com',
                    'de' => 'contact@bodyluxa.com',
                    'tr' => 'contact@bodyluxa.com'
                ]
            ],
            [
                'key' => 'contact_phone',
                'type' => 'phone',
                'description' => 'Téléphone de contact principal',
                'values' => [
                    'fr' => '+33 1 23 45 67 89',
                    'en' => '+33 1 23 45 67 89',
                    'de' => '+33 1 23 45 67 89',
                    'tr' => '+33 1 23 45 67 89'
                ]
            ],
            [
                'key' => 'whatsapp_number',
                'type' => 'phone',
                'description' => 'Numéro WhatsApp',
                'values' => [
                    'fr' => '+905551234567',
                    'en' => '+905551234567',
                    'de' => '+905551234567',
                    'tr' => '+905551234567'
                ]
            ],
            [
                'key' => 'company_address',
                'type' => 'textarea',
                'description' => 'Adresse de l\'entreprise',
                'values' => [
                    'fr' => 'Istanbul, Turquie',
                    'en' => 'Istanbul, Turkey',
                    'de' => 'Istanbul, Türkei',
                    'tr' => 'İstanbul, Türkiye'
                ]
            ],
            [
                'key' => 'hero_description',
                'type' => 'textarea',
                'description' => 'Description principale de la page d\'accueil',
                'values' => [
                    'fr' => 'Votre partenaire de confiance pour des soins médicaux et esthétiques de qualité en Turquie. Des prix imbattables, des résultats exceptionnels.',
                    'en' => 'Your trusted partner for quality medical and aesthetic care in Turkey. Unbeatable prices, exceptional results.',
                    'de' => 'Ihr vertrauenswürdiger Partner für qualitativ hochwertige medizinische und ästhetische Behandlungen in der Türkei. Unschlagbare Preise, außergewöhnliche Ergebnisse.',
                    'tr' => 'Türkiye\'de kaliteli tıbbi ve estetik bakım için güvenilir ortağınız. Rakipsiz fiyatlar, olağanüstü sonuçlar.'
                ]
            ]
        ];

        foreach ($configs as $configData) {
            $config = new Config();
            $config->setConfigKey($configData['key']);
            $config->setConfigType($configData['type']);
            $config->setDescription($configData['description']);
            $config->setIsActive(true);

            // Set default value (French)
            if (isset($configData['values']['fr'])) {
                $config->setConfigValue($configData['values']['fr']);
            }

            $manager->persist($config);

            // Add translations
            foreach ($configData['values'] as $langCode => $value) {
                $language = match($langCode) {
                    'fr' => $frLanguage,
                    'en' => $enLanguage,
                    'de' => $deLanguage,
                    'tr' => $trLanguage,
                    default => null
                };

                if ($language) {
                    $translation = new ConfigTranslation();
                    $translation->setConfig($config);
                    $translation->setLanguage($language);
                    $translation->setValue($value);
                    $manager->persist($translation);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}
