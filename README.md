# _Sunu Framework_
Utilisez cette architecture pour démarrer rapidement un nouveau projet. !

**Prérequis**

ouvrir le terminal à la racine du projet et tapez la commande suivante
    
    $ composer install
    
**Générateur**

ouvrir le terminal à la racine du projet

    Générer un crud
    $ php maker makecrud -i (mode interactif)
    ou
    $ php maker makecrud --table:valeur --rename:valeur --space:valeur
    --table:valeur = `valeur` représente le nom de la table dans la BD
    --rename:valeur = `valeur` représente le nom qui sera utilisé générer le controlleur, le model et la vue
    --space:valeur = `valeur` représente l'espace dans le quel sera généré le crud
 

    Supprimer un crud
    $ php maker unmakecrud -i (mode interactif)
    ou
    $ php maker unmakecrud --rename:valeur --space:valeur
    --table:valeur = `valeur` représente le nom de la table dans la BD
    --rename:valeur = `valeur` représente le nom qui sera utilisé générer le controlleur, le model et la vue
    --space:valeur = `valeur` représente l'espace dans le quel sera généré le crud
    
    NB : si vous ne définissez pas l'argument --space:valeur c l'espace par defaut qui sera utiliser.