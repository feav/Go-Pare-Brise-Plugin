<?php
/*
Plugin Name: GO-PARE-BRISE
Description: Un plugin pour générer des rapports au format PDF.
Version: 1.0
Author: Armel <feavfeav@gmail.com>
*/

// Sécurité: Vérifier si WordPress est chargé directement.
defined('ABSPATH') or die('Accès interdit');

class GoPareBrisePlugin {
    public function __construct() {
        // Ajoutez ici vos hooks et actions WordPress.
         add_action('init', array($this, 'generer_rapport_pdf'));
         add_action('admin_menu', array($this, 'ajouter_menu_administration'));
         add_action('init', array($this, 'creer_post_type_intervention'));
         add_action('rest_api_init', array($this, 'ajouter_endpoint_api'));

    }

     // Méthode pour ajouter un endpoint à l'API REST.
     public function ajouter_endpoint_api() {
        register_rest_route('gpb/v1', '/intervention', array(
            'methods' => 'POST',
            'callback' => array($this, 'enregistrer_intervention_via_api'),
            'permission_callback' => '__return_true', // Autoriser l'accès à tous les utilisateurs.
        ));
    }

    public function enregistrer_intervention_via_api($data) {
        // Vérifiez les données JSON reçues.
        $json_data = $data->get_params();
    
        // Créez un nouvel objet Intervention avec les données JSON.
        $nouvelle_intervention = new Intervention();
        // ... (Code pour attribuer les valeurs JSON aux propriétés de l'objet Intervention)
    
        // Récupérez les propriétés de l'intervention en tant que tableau associatif.
        $intervention_data = (array) $nouvelle_intervention;
    
        // Enregistrez l'intervention en tant que nouveau post de type "intervention".
        $post_id = wp_insert_post(array(
            'post_title' => 'Intervention de ' . $json_data['nomClient'], // Titre du post.
            'post_type' => 'intervention', // Type de post.
            'post_status' => 'publish', // Statut du post.
        ));
    
        // Enregistrez chaque attribut de l'intervention en tant que post meta individuel.
        foreach ($intervention_data as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
    
        // Réponse JSON avec le statut de l'opération.
        $response = new WP_REST_Response(array('success' => true, 'message' => 'Intervention enregistrée avec succès.'));
        return $response;
    }

    
    // Fonction pour créer le type de publication personnalisé "intervention".
    public function creer_post_type_intervention() {
        $labels = array(
            'name'               => 'Interventions',
            'singular_name'      => 'Intervention',
            'menu_name'          => 'Interventions',
            'name_admin_bar'     => 'Intervention',
            'add_new'            => 'Ajouter Nouvelle',
            'add_new_item'       => 'Ajouter Nouvelle Intervention',
            'new_item'           => 'Nouvelle Intervention',
            'edit_item'          => 'Modifier Intervention',
            'view_item'          => 'Voir Intervention',
            'all_items'          => 'Toutes les Interventions',
            'search_items'       => 'Rechercher les Interventions',
            'parent_item_colon'  => 'Interventions Parentes :',
            'not_found'          => 'Aucune intervention trouvée.',
            'not_found_in_trash' => 'Aucune intervention trouvée dans la corbeille.'
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'intervention' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'register_meta_box_cb' => array($this, 'ajouter_meta_box_intervention') // Appel à la fonction pour ajouter les meta boxes.
        );

        register_post_type('intervention', $args);
    }

    // Fonction pour ajouter les meta boxes pour le type de publication "intervention".
    public function ajouter_meta_box_intervention() {
        add_meta_box('intervention_meta', 'Détails de l\'Intervention', array($this, 'afficher_meta_box_intervention'), 'intervention', 'normal', 'high');
    }

    // Fonction pour enregistrer les données de la meta box "Détails de l'Intervention".
    public function enregistrer_meta_box_intervention($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
            return $post_id;

        if ($_POST && isset($_POST['intervention_data'])) {
            $intervention_data = json_decode(stripslashes($_POST['intervention_data']), true);
            update_post_meta($post_id, 'intervention_data', $intervention_data);
        }
    }
    // Exemple de fonction pour générer un rapport PDF.
    
    public function generer_rapport_pdf() {
        // Code pour générer le PDF ici en utilisant la bibliothèque choisie (TCPDF, FPDF, etc.).
    }

    // Exemple de fonction pour ajouter un menu d'administration.
    public function ajouter_menu_administration() {
        add_menu_page('GO-PARE-BRISE', 'GO-PARE-BRISE', 'manage_options', 'go-pare-brise', array($this, 'afficher_page_administration'));
    }

    public function afficher_page_administration() {
        // Contenu de la page d'administration ici.
    }
    // Fonction pour afficher le contenu de la méta-boîte "Détails de l'Intervention".
    public function afficher_meta_box_intervention($post) {
        // Récupérez les données d'intervention associées au post actuel.
        $intervention_data = get_post_meta($post->ID, 'intervention_data', true);

        // Si des données d'intervention existent, décodez-les en tableau associatif.
        if (!empty($intervention_data)) {
            $intervention = json_decode($intervention_data, true);
        } else {
            // Si aucune donnée n'existe, créez un tableau vide pour stocker les valeurs par défaut.
            $intervention = array(
                'id' => 0,
                'serverId' => 0,
                'nomClient' => '',
                'prenomClient' => '',
                'villeClient' => '',
                'emailClient' => '',
                'codePostalClient' => '',
                'adresseClient' => '',
                'telClient' => '',
                'marqueVehicle' => '',
                'modeleVehicle' => '',
                'immatriculationVehicle' => '',
                'kilometrageVehicle' => '',
                'assuranceVehicle' => '',
                'policeVehicle' => '',
                'miseCirculationVehicule' => '',
                'dateSinistre' => '',
                'lieuSinistre' => '',
                'departementSinistre' => '',
                'circonstanceSinistre' => '',
                'elementSinistre' => '',
                'tva' => '',
                'dateIntervention' => '',
                'devisIntervention' => '',
                'numIntervention' => '',
                'reglementIntervention' => 'cb',
                'franchiseIntervention' => '',
                'dateSignature' => '',
                'lieuSignature' => '',
                'signature' => ''
            );
        }
        ?>

        <!-- Formulaire de saisie des données d'intervention -->
        <label for="nomClient">Nom du Client:</label>
        <input type="text" id="nomClient" name="intervention_data[nomClient]" value="<?php echo esc_attr($intervention['nomClient']); ?>" required><br>

        <label for="prenomClient">Prénom du Client:</label>
        <input type="text" id="prenomClient" name="intervention_data[prenomClient]" value="<?php echo esc_attr($intervention['prenomClient']); ?>"><br>

        <label for="villeClient">Ville du Client:</label>
        <input type="text" id="villeClient" name="intervention_data[villeClient]" value="<?php echo esc_attr($intervention['villeClient']); ?>"><br>

        <label for="emailClient">Email du Client:</label>
        <input type="email" id="emailClient" name="intervention_data[emailClient]" value="<?php echo esc_attr($intervention['emailClient']); ?>"><br>

        <label for="codePostalClient">Code Postal du Client:</label>
        <input type="text" id="codePostalClient" name="intervention_data[codePostalClient]" value="<?php echo esc_attr($intervention['codePostalClient']); ?>"><br>

        <label for="adresseClient">Adresse du Client:</label>
        <input type="text" id="adresseClient" name="intervention_data[adresseClient]" value="<?php echo esc_attr($intervention['adresseClient']); ?>"><br>

        <label for="telClient">Téléphone du Client:</label>
        <input type="tel" id="telClient" name="intervention_data[telClient]" value="<?php echo esc_attr($intervention['telClient']); ?>"><br>

        <label for="marqueVehicle">Marque du Véhicule:</label>
        <input type="text" id="marqueVehicle" name="intervention_data[marqueVehicle]" value="<?php echo esc_attr($intervention['marqueVehicle']); ?>"><br>

        <label for="modeleVehicle">Modèle du Véhicule:</label>
        <input type="text" id="modeleVehicle" name="intervention_data[modeleVehicle]" value="<?php echo esc_attr($intervention['modeleVehicle']); ?>"><br>

        <label for="immatriculationVehicle">Immatriculation du Véhicule:</label>
        <input type="text" id="immatriculationVehicle" name="intervention_data[immatriculationVehicle]" value="<?php echo esc_attr($intervention['immatriculationVehicle']); ?>"><br>

        <label for="kilometrageVehicle">Kilométrage du Véhicule:</label>
        <input type="text" id="kilometrageVehicle" name="intervention_data[kilometrageVehicle]" value="<?php echo esc_attr($intervention['kilometrageVehicle']); ?>"><br>

        <label for="assuranceVehicle">Assurance du Véhicule:</label>
        <input type="text" id="assuranceVehicle" name="intervention_data[assuranceVehicle]" value="<?php echo esc_attr($intervention['assuranceVehicle']); ?>"><br>

        <label for="policeVehicle">Numéro de Police d'Assurance:</label>
        <input type="text" id="policeVehicle" name="intervention_data[policeVehicle]" value="<?php echo esc_attr($intervention['policeVehicle']); ?>"><br>

        <label for="miseCirculationVehicule">Date de Mise en Circulation du Véhicule:</label>
        <input type="text" id="miseCirculationVehicule" name="intervention_data[miseCirculationVehicule]" value="<?php echo esc_attr($intervention['miseCirculationVehicule']); ?>"><br>

        <label for="dateSinistre">Date du Sinistre:</label>
        <input type="text" id="dateSinistre" name="intervention_data[dateSinistre]" value="<?php echo esc_attr($intervention['dateSinistre']); ?>"><br>

        <label for="lieuSinistre">Lieu du Sinistre:</label>
        <input type="text" id="lieuSinistre" name="intervention_data[lieuSinistre]" value="<?php echo esc_attr($intervention['lieuSinistre']); ?>"><br>

        <label for="departementSinistre">Département du Sinistre:</label>
        <input type="text" id="departementSinistre" name="intervention_data[departementSinistre]" value="<?php echo esc_attr($intervention['departementSinistre']); ?>"><br>

        <label for="circonstanceSinistre">Circonstances du Sinistre:</label>
        <input type="text" id="circonstanceSinistre" name="intervention_data[circonstanceSinistre]" value="<?php echo esc_attr($intervention['circonstanceSinistre']); ?>"><br>

        <label for="elementSinistre">Éléments du Sinistre:</label>
        <input type="text" id="elementSinistre" name="intervention_data[elementSinistre]" value="<?php echo esc_attr($intervention['elementSinistre']); ?>"><br>

        <label for="tva">TVA (oui ou non):</label>
        <input type="text" id="tva" name="intervention_data[tva]" value="<?php echo esc_attr($intervention['tva']); ?>"><br>

        <label for="dateIntervention">Date de l'Intervention:</label>
        <input type="text" id="dateIntervention" name="intervention_data[dateIntervention]" value="<?php echo esc_attr($intervention['dateIntervention']); ?>"><br>

        <label for="devisIntervention">Devis de l'Intervention:</label>
        <input type="text" id="devisIntervention" name="intervention_data[devisIntervention]" value="<?php echo esc_attr($intervention['devisIntervention']); ?>"><br>

        <label for="numIntervention">Numéro de l'Intervention:</label>
        <input type="text" id="numIntervention" name="intervention_data[numIntervention]" value="<?php echo esc_attr($intervention['numIntervention']); ?>"><br>

        <label for="reglementIntervention">Règlement de l'Intervention (e, cb ou cc):</label>
        <input type="text" id="reglementIntervention" name="intervention_data[reglementIntervention]" value="<?php echo esc_attr($intervention['reglementIntervention']); ?>"><br>

        <label for="franchiseIntervention">Franchise de l'Intervention:</label>
        <input type="text" id="franchiseIntervention" name="intervention_data[franchiseIntervention]" value="<?php echo esc_attr($intervention['franchiseIntervention']); ?>"><br>

        <label for="dateSignature">Date de Signature:</label>
        <input type="text" id="dateSignature" name="intervention_data[dateSignature]" value="<?php echo esc_attr($intervention['dateSignature']); ?>"><br>

        <label for="lieuSignature">Lieu de Signature:</label>
        <input type="text" id="lieuSignature" name="intervention_data[lieuSignature]" value="<?php echo esc_attr($intervention['lieuSignature']); ?>"><br>

        <label for="signature">Signature:</label>
        <input type="text" id="signature" name="intervention_data[signature]" value="<?php echo esc_attr($intervention['signature']); ?>"><br>

        <!-- Ajoutez ici d'autres champs pour les autres attributs de l'intervention -->

        <?php
    }
}

// Instanciez la classe principale du plugin.
$go_pare_brise_plugin = new GoPareBrisePlugin();
