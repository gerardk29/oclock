Jeu de mémoire

Application web de jeu de memory codée en PHP et Javascript.
Temps imparti de 2 minutes pour trouver toutes les paires de cartes.

Comment jouer :
Une fois le nom du joueur saisi, la partie commence.
Le joueur a 2 minutes pour découvrir les 14 paires de cartes.
Le site affiche les 5 meilleurs scores (temps les plus rapides).

Evolutions possibles:
- avoir plusieurs niveaux de difficultés (en nombre de cartes et en temps imparti)
- permettre de choisir un thème parmi plusieurs au choix (couleurs du site, images des cartes)
- ajouter des animations (notamment des effets de retournement sur les cartes (flip), message succès animé)

Architecture :
Pour la partie frontend : l'application est codée en Javascript (avec des utilisations de la bibliothèque jQuery). Utilisation de Sass pour gérer le CSS.
Pour la partie backend : l'application est codée en PHP orienté objet.
La page index.php demande au joueur de renseigner son nom, puis redirige vers la page game.php
La page game.php affiche :
- les 5 meilleurs temps
- les 28 cartes à découvrir
- un compteur de temps (0 à 2 min)
- une barre de progression (qui se vide à mesure que le temps passe)
- un message de succès si le joueur trouve toutes les cartes avant 2 minutes
- un message d'échec sinon
- à la fin du jeu, une nouvelle partie est automatiquement proposée

Controlleurs :
- CardController : prépare les images du tableau de jeu
- GameController : gère la logique du jeu (initialise les paramètres de jeu, définit les getteurs et setteurs, l'algorithme principal du jeu)

Modèles :
- ConnectModel : fait la connexion à la base de données
- GameManager : fait la sauvegarde des parties gagnées en base de données et en session
- GameRepository : requête la base de données pour l'affichage des meilleurs scores et les stocke en session

NB : bien que ce projet soit à but pédagogique, les commentaires du code sont tous en anglais afin de plonger les apprenants dans le futur monde professionnel.
