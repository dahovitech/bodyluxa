# BodyLuxa - Guide de Démarrage Rapide

## 🚀 Accès Immédiat

**Votre site est déjà accessible sur :**
🌐 **http://localhost:8000**

## 📱 Fonctionnalités Disponibles

### Pages Principales
- **Accueil** : `/` - Hero, services, calculateur prix
- **Contact** : `/contact` - Formulaires interactifs avec API
- **Services** : `/services` - Catalogue détaillé
- **À propos** : `/about` - Informations entreprise

### Fonctionnalités Interactives
✅ **Calculateur de Prix** - Estimation temps réel
✅ **Formulaires AJAX** - Contact/Devis sans rechargement
✅ **Animations Modernes** - Scroll effects, hover animations
✅ **Design Responsive** - Parfait sur mobile/tablet/desktop
✅ **Navigation Intelligente** - Menu fixe avec effets

## 🔧 Commandes Utiles

```bash
# Redémarrer le serveur
cd /workspace/bodyluxa
php -S localhost:8000 -t public

# Build des assets
npm run build    # Production
npm run dev      # Développement

# Symfony commands
php bin/console cache:clear
php bin/console debug:router
```

## 🎨 Personnalisation Rapide

### Changer les Couleurs
```css
/* Dans assets/styles/modern.css */
:root {
    --primary-color: #2c3e50;
    --secondary-color: #e74c3c;
    --accent-color: #3498db;
}
```

### Modifier le Contenu
```yaml
# Dans translations/messages.fr.yaml
hero_title: "Votre nouveau titre"
company_description: "Votre description"
```

## 📊 Performance

✅ **Build Optimisé** : Assets minifiés (76.5 KiB)
✅ **Code Quality** : Architecture modulaire
✅ **API Performante** : Réponses JSON rapides
✅ **SEO Ready** : Structure optimisée

---

**Votre site BodyLuxa est prêt !** 🎆

*Visitez http://localhost:8000 pour voir le résultat*
