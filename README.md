# Richard — site KRAAK Associates (WordPress)

Thème WordPress sur mesure du bureau d'architecture **KRAAK Associates**, développé d'après la
maquette haute définition et le cahier des charges (desktop + mobile).

Le thème se trouve dans [`wp-content/themes/kraak`](wp-content/themes/kraak).

## Installation

1. Compresser le dossier `wp-content/themes/kraak` en `kraak.zip` (ou utiliser le zip fourni).
2. WordPress › **Apparence › Thèmes › Ajouter › Téléverser** → `kraak.zip` → **Activer**.
3. **Apparence › Contenu de démo KRAAK › Importer le contenu de démo** : crée les pages, le menu,
   9 projets, 5 recherches, les distinctions et les images de la maquette.
4. **Réglages › Permaliens** : choisir « Titre de la publication » puis enregistrer.
5. Remplacer les textes entre `[crochets]` (adresse, titres de projets, biographie…).

Extensions recommandées (facultatives) :

- **WP Mail SMTP** : envoi fiable des messages du formulaire de contact.
- **Polylang** : version anglaise (le sélecteur FR / EN du menu s'active automatiquement).

## Où modifier quoi

| Contenu | Emplacement dans l'administration |
| --- | --- |
| Projets (fiche, galerie, plans, coupes, schémas, diagrammes) | **Projets** |
| Projets du diaporama d'accueil | Case « À la une » dans la fiche projet |
| Ordre des projets | Champ « Ordre » (attributs) de chaque projet |
| Recherches du Laboratoire | **Laboratoire** (case « Recherche à la une ») |
| Prix, concours, expositions, publications, conférences | **Distinctions** (catégorie + année + mention) |
| Slogan, textes de l'accueil, page Studio, coordonnées, réseaux | **Apparence › Personnaliser › KRAAK Associates** |
| Menu | **Apparence › Menus** (emplacement « Menu principal ») |
| Logo (facultatif, sinon logo SVG intégré) | **Apparence › Personnaliser › Identité du site** |

## Architecture du site

```
Accueil (/)                       front-page.php
├── Projets (/projets/)           archive-projet.php   filtres ?type=, grille / liste, « Charger plus »
│   └── Fiche projet              single-projet.php    fiche, intention, galerie, plans, projet suivant
├── Laboratoire (/laboratoire/)   archive-recherche.php axes, index filtrable, recherche à la une
│   └── Fiche recherche           single-recherche.php
├── Studio (/studio/)             page-studio.php      manifeste, fondateur, parcours, philosophie
├── Distinctions (/distinctions/) archive-distinction.php  groupées par catégorie
├── Contact (/contact/)           page-contact.php     coordonnées, carte, formulaire
└── Mentions légales              page.php
```

```
wp-content/themes/kraak/
├── style.css               en-tête du thème
├── functions.php           chargement des modules inc/
├── inc/
│   ├── setup.php           supports, menus, tailles d'images, scripts
│   ├── post-types.php      types de contenus projet / recherche / distinction + taxonomies
│   ├── meta-boxes.php      champs personnalisés (sans extension), galeries d'images
│   ├── customizer.php      options : textes, coordonnées, images
│   ├── template-tags.php   logo, repères, flèches, navigation, sélecteur de langue
│   ├── queries.php         tri, pagination et filtres des archives
│   ├── contact-form.php    formulaire (nonce, anti-robots, limite d'envoi, wp_mail)
│   └── demo-content.php    import du contenu de démonstration
├── template-parts/card-projet.php
├── assets/css/main.css     styles (responsive)
├── assets/js/main.js       menu mobile, diaporama, filtres, onglets de plans, zoom
├── assets/fonts/           Poppins + IBM Plex Mono (hébergées localement)
└── assets/demo/            images de la maquette (import de démo)
```

Aucune extension n'est requise : types de contenus, champs, formulaire et options sont intégrés au thème.
