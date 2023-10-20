# GoPareBrise WordPress Plugin

Ce plugin WordPress vous permet de gérer les interventions liées aux réparations de pare-brise. Il fournit une interface conviviale pour enregistrer, afficher et gérer les détails des interventions, ainsi qu'une API pour enregistrer les interventions via des requêtes JSON.

## Fonctionnalités

- Création d'interventions avec toutes les informations pertinentes.
- Stockage des détails de l'intervention en tant que post meta individuels pour une gestion flexible.
- Intégration d'une API REST pour enregistrer les interventions via des requêtes JSON.
- Affichage des interventions dans l'interface d'administration WordPress.
- Possibilité d'éditer et de supprimer les interventions existantes.

## Installation

1. Clonez ou téléchargez ce référentiel dans le dossier `wp-content/plugins/` de votre installation WordPress.
2. Activez le plugin via l'interface d'administration WordPress.

## Utilisation

Une fois le plugin activé, vous pouvez accéder à la section "Interventions" dans le menu d'administration WordPress. Vous pouvez y ajouter de nouvelles interventions, les éditer ou les supprimer.

### Enregistrement via l'API

Vous pouvez également enregistrer de nouvelles interventions en utilisant l'API REST. Faites une requête POST à l'URL suivante :


Envoyez un objet JSON avec les détails de l'intervention que vous souhaitez enregistrer. Assurez-vous de fournir toutes les informations nécessaires conformément à la structure de l'objet `Intervention`.

Exemple de requête :

```json
{
    "nomClient": "John Doe",
    "emailClient": "john.doe@example.com",
    "villeClient": "Paris",
    "marqueVehicle": "Toyota",
    "modeleVehicle": "Corolla",
    "immatriculationVehicle": "AB 123 CD"
    // ... (autres attributs de l'intervention)
}
