# BodyLuxa - Medical Tourism Platform

BodyLuxa est une plateforme moderne de tourisme médical développée avec Symfony 7.3, inspirée de l'expertise de Body Expert. Cette application web offre une expérience utilisateur exceptionnelle pour les patients recherchant des soins médicaux et esthétiques de qualité en Turquie.

## 🌟 Fonctionnalités Principales

### 🏥 Services Médicaux
- **Greffe de Cheveux** : Techniques FUE et DHI de dernière génération
- **Soins Dentaires** : Implants, couronnes, facettes, blanchiment
- **Chirurgie Esthétique** : Rhinoplastie, augmentation mammaire, liposuccion
- **Chirurgie Oculaire** : LASIK, cataracte, blépharoplastie
- **Chirurgie Bariatrique** : Sleeve, bypass, ballon gastrique
- **Fécondation In Vitro** : Traitements de fertilité avancés

### 🌍 Multi-langue
- **Français** (par défaut)
- **Anglais**
- **Allemand** 
- **Turc**

### ⚙️ Personnalisation Dynamique
- Entité `Config` pour la personnalisation des informations
- Traductions dynamiques avec l'entité `ConfigTranslation`
- Interface d'administration pour la gestion de contenu

## 🛠 Technologies Utilisées

- **Framework** : Symfony 7.3
- **Frontend** : Webpack Encore, Bootstrap 5, FontAwesome
- **Base de données** : SQLite (développement)
- **Templating** : Twig
- **Traductions** : Symfony Translation Component
- **Styling** : CSS personnalisé avec design responsive

## 🚀 Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- Node.js et npm/yarn
- SQLite

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone https://github.com/dahovitech/bodyluxa.git
cd bodyluxa
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Configurer l'environnement**
```bash
cp .env .env.local
# Modifier les variables d'environnement si nécessaire
```

4. **Créer la base de données et exécuter les migrations**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Charger les fixtures (données de test)**
```bash
php bin/console doctrine:fixtures:load
```

6. **Installer et compiler les assets**
```bash
npm install
npm run build
```

7. **Démarrer le serveur de développement**
```bash
# Vider le cache Symfony
php bin/console cache:clear

# Démarrer le serveur
symfony serve
# ou
php -S localhost:8000 -t public
```

## 📱 Design et Expérience Utilisateur

### Inspiration Body Expert
Le design s'inspire du site Body Expert avec :
- Interface moderne et professionnelle
- Navigation intuitive
- Call-to-actions optimisés
- Témoignages patients
- Processus de traitement clair

### Fonctionnalités UX
- Design responsive (mobile-first)
- Animations CSS fluides
- Navigation multi-langue
- Formulaires de contact optimisés
- Intégration WhatsApp
- Section FAQ interactive

## 🏗 Architecture

### Entités Principales
- **Language** : Gestion des langues
- **Service** : Services médicaux
- **ServiceTranslation** : Traductions des services
- **Config** : Configuration dynamique du site
- **ConfigTranslation** : Traductions des configurations

### Contrôleurs
- **HomeController** : Pages principales (accueil, services, contact, à propos)
- **ServiceController** : Gestion des services
- **Admin Controllers** : Interface d'administration

### Services Métier
- **ConfigService** : Gestion de la configuration dynamique
- **TranslationService** : Gestion des traductions

## 🔧 Configuration Multi-langue

Les routes sont configurées avec préfixes de langue :
- `/fr/` - Français (par défaut)
- `/en/` - Anglais
- `/de/` - Allemand
- `/tr/` - Turc

## 👥 Équipe

**Développé par** : Prudence ASSOGBA  
**Email** : jprud67@gmail.com  
**Pour** : BodyLuxa Medical Tourism

## 📄 Licence

Ce projet est propriétaire de BodyLuxa. Tous droits réservés.

## 🤝 Contribution

Pour contribuer au projet, veuillez :
1. Créer une branche feature
2. Commiter vos changements
3. Créer une Pull Request

## 📞 Support

Pour toute question ou support :
- Email : contact@bodyluxa.com
- WhatsApp : +905551234567

---

**BodyLuxa - Votre partenaire de confiance pour le tourisme médical** 🏥✈️
