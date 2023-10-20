<?php

class Intervention {
    public $id = 0;
    public $serverId = 0;
    public $nomClient = "";
    public $prenomClient = "";
    public $villeClient = "";
    public $emailClient = "";
    public $codePostalClient = "";
    public $adresseClient = "";
    public $telClient = "";

    public $marqueVehicle = "";
    public $modeleVehicle = "";
    public $immatriculationVehicle = "";
    public $kilometrageVehicle = "";
    public $assuranceVehicle = "";
    public $policeVehicle = "";
    public $miseCirculationVehicule = ""; // Date au format texte (ex. "YYYY-MM-DD")

    public $dateSinistre = ""; // Date au format texte (ex. "YYYY-MM-DD")
    public $lieuSinistre = "";
    public $departementSinistre = "";
    public $circonstanceSinistre = "";
    public $elementSinistre = "";
    public $tva = ""; // "oui" ou "non"

    public $dateIntervention = ""; // Date au format texte (ex. "YYYY-MM-DD")
    public $devisIntervention = "";
    public $numIntervention = "";
    public $reglementIntervention = "cb"; // "e", "cb" ou "cc"
    public $franchiseIntervention = "";

    public $dateSignature = ""; // Date au format texte (ex. "YYYY-MM-DD")
    public $lieuSignature = "";
}

?>