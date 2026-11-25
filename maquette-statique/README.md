# KRAAK Associates — maquette statique cliquable

Version **HTML / CSS / JavaScript pure** du site (sans WordPress, sans base de données).
Toutes les pages de la maquette haute définition sont reliées entre elles.

## Ouvrir la maquette

- **Sur ton ordinateur :** double-clic sur `index.html`.
- **En ligne :** déposer tout le dossier chez n'importe quel hébergeur statique
  (GitHub Pages, Netlify, Vercel, OVH…).

## Pages

| Page | Fichier |
| --- | --- |
| Accueil (diaporama, studio, projets, laboratoire, distinctions, contact) | `index.html` |
| Projets — grille filtrable, vue grille / liste | `projets/index.html` |
| 9 fiches projets (fiche, intention, galerie, plans / coupes / schémas / diagrammes, projet suivant) | `projets/*/index.html` |
| Laboratoire — axes, index filtrable, recherche à la une | `laboratoire/index.html` |
| 5 fiches recherches | `laboratoire/*/index.html` |
| Studio — manifeste, fondateur, parcours, philosophie | `studio/index.html` |
| Distinctions — prix, concours, expositions, publications, conférences | `distinctions/index.html` |
| Contact — coordonnées, carte, formulaire | `contact/index.html` |
| Mentions légales | `mentions-legales/index.html` |

## Ce qui est interactif

- Menu principal, logo, pied de page, boutons et cartes : liens vers les vraies pages.
- Diaporama de l'accueil (automatique, clic sur 01–05, balayage sur mobile).
- Filtres Projets (Architecture, Recherche, Concours, Expérimental) et Laboratoire.
- Vue Grille / Liste sur la page Projets.
- Onglets Plans / Coupes / Schémas / Diagrammes et zoom sur les plans.
- Menu mobile plein écran.
- Formulaire de contact : ouvre la messagerie avec le message pré-rempli
  (pas d'envoi serveur dans une maquette statique).

Les textes entre `[crochets]` sont à remplacer par les contenus définitifs.
