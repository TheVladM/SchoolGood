# Notes et Instructions – Projet de Plateforme Scolaire

## Informations générales

### Gestion des utilisateurs

- Créer des informations sur :
  - les enseignants ;
  - les responsables administratifs de l’école.

Exemple :  
Certaines personnes travaillent à la scolarité et s’occupent de la pension.  
Ces personnes font également partie du personnel autorisé à créer les informations sur les élèves et leurs parents.

- L’administrateur de la plateforme crée toutes les personnes :
  - parents ;
  - élèves ;
  - responsables de l’école.

Donc, c’est l’administrateur qui crée tous les comptes.

---

## Gestion des enseignants et des classes

- C’est l’administrateur qui décide, parmi tous les enseignants disponibles, qui sera enseignant titulaire d’une classe.
- Un enseignant titulaire est associé à une salle de cours.
- Une salle de cours est reliée à une classe.

### Cas des classes SIL à CM2

Pour chaque classe, il y a deux enseignants.

#### Chez les francophones

- Un enseignant qui dispense toutes les matières francophones.
- Un enseignant qui dispense le cours d’anglais.

#### Chez les anglophones

- Un enseignant qui dispense toutes les matières anglophones.
- Un enseignant qui dispense le cours de français.

### Règle complémentaire

- Un enseignant d’anglais dans une ou plusieurs classes francophones peut être titulaire d’une et une seule classe anglophone, et inversement.

---

## Niveaux et types de classes

### Classes francophones

- Crèche
- Petite section
- Moyenne section
- Grande section
- SIL
- CP
- CE1
- CE2
- CM1
- CM2

### Classes anglophones

- Kindergarten
- Nursery 1
- Nursery 2
- Nursery 3
- Class 1
- Class 2
- Class 3
- Class 4
- Class 5
- Class 6

### Multiples classes par niveau

Pour un même niveau, on peut avoir plusieurs classes.

Exemple :

- CM1-A
- CM1-B
- CM1-C

Autant que l’administrateur le souhaite.

### Signification des sigles

- CM = Cours Moyen
- CE = Cours Élémentaire
- CP = Cours Préparatoire
- SIL = Section d’Initiation à la Lecture

---

## Gestion des salles de cours

Pour une classe donnée, par exemple **CM1-A** :

- une salle de cours lui est affectée ;
- cette salle possède un emplacement physique précis dans l’école.

Exemples :

- rez-de-chaussée ;
- premier étage.

Ces informations sont créées et gérées par l’administrateur.

---

## Gestion pédagogique

Les enseignants :

- gèrent entièrement leur classe sur la plateforme ;
- disposent de leur salle ;
- enseignent plusieurs matières :
  - français ;
  - mathématiques ;
  - informatique ;
  - dessin ;
  - calcul rapide ;
  - etc.

Ils peuvent :

- donner des devoirs ;
- publier les cours ;
- mettre les informations sur la plateforme afin que :
  - les parents ;
  - le personnel administratif

puissent consulter les cours et devoirs du jour ou de la semaine.

### Attribution des cours

L’administrateur :

- crée les enseignants ;
- peut les titulariser ;
- peut attribuer un ou plusieurs cours à un enseignant.

---

## Gestion de la bibliothèque

L’école possède une bibliothèque physique.

Tous les livres doivent être enregistrés sur la plateforme afin d’assurer :

- la traçabilité ;
- le suivi des emprunts.

### Emprunts

Lorsqu’un enseignant ou un élève emprunte un livre :

- le livre est marqué comme emprunté dans la plateforme ;
- chaque jour de retard est comptabilisé ;
- des pénalités peuvent être appliquées après dépassement de la limite autorisée.

### Gestion

Cette partie est gérée par :

- l’administrateur ;
- les membres de la scolarité.

---

## Gestion des années scolaires

Après la fin d’année scolaire et la remise des diplômes :

- les élèves doivent être automatiquement déplacés vers la classe du niveau supérieur ;
- cela permet de préparer automatiquement leur réinscription l’année suivante.

### Historique

Même après changement de classe ou suppression d’un élève :

- toutes les anciennes informations doivent rester accessibles :
  - notes ;
  - résultats ;
  - historique scolaire ;
  - anciennes classes.

---

## Emploi du temps

Chaque niveau possède un emploi du temps spécifique.

Exemple :

- CM1-A et CM1-B ont le même emploi du temps car ils appartiennent au même niveau.

### Informations à gérer

Pour chaque cours :

- jour ;
- plage horaire ;
- type de cours.

### Formulaire intelligent

Au lieu d’écrire les jours manuellement :

- le formulaire doit proposer une liste déroulante des 6 jours ouvrables de la semaine.

---

## Gestion des cycles et sections

La plateforme doit permettre de gérer :

- des crèches mixtes ;
- des crèches francophones ;
- des crèches anglophones ;
- des cycles bilingues ;
- des primaires bilingues ;
- des maternelles bilingues.

---

## Gestion des paiements

Les parents peuvent payer :

- frais d’inscription ;
- première tranche ;
- deuxième tranche ;
- troisième tranche.

### Moyens de paiement

- Orange Money
- MTN Mobile Money
- Compte bancaire

Avec :

- numéro de compte ;
- informations nécessaires au paiement.

---

## Gestion des frais de scolarité

Le droit de gérer les frais de scolarité appartient exclusivement :

- au fondateur de l’école ;
- aux membres de la scolarité.

### Restrictions

Même le directeur ne peut pas gérer :

- les frais de scolarité ;
- les montants par niveau ;
- les montants par classe.

Le fondateur est, par défaut, un administrateur dans le système.

---

## Validation des messages aux parents

Les membres de la scolarité peuvent rédiger des messages à destination des parents.

Cependant :

- le fondateur voit tous les messages ;
- il peut les approuver ou les invalider.

Un message n’est envoyé qu’après validation du fondateur depuis son interface administrateur.