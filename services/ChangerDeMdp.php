<?php
// Service web du projet Réservations M2L
// Ecrit le 22/11/2016 par Patrick
// Ecrit le 22/11/2016 par Patrick

// Ce service web permet à un administrateur authentifié de changer son mot de passe
// et fournit un compte-rendu d'exécution

// Le service web doit être appelé avec 4 paramètres : name
// Les paramètres peuvent être passés par la méthode GET (pratique pour les tests, mais à éviter en exploitation) :
//     http://<hébergeur>/ChangerDeMdp?name=morel&ancienMdp=ab&nouveauMdp=123&confirmationMdp=1234

// Les paramètres peuvent être passés par la méthode POST (à privilégier en exploitation pour la confidentialité des données) :
//     http://<hébergeur>/DemanderMdp.php

// inclusion de la classe Outils
include_once ('../modele/Outils.class.php');
// inclusion des paramètres de l'application
include_once ('../modele/parametres.localhost.php');

// Récupération des données transmises
// la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
if ( empty ($_GET ["name"]) == true)  $name = "";  else   $name = $_GET ["name"];

// si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
// la fonction $_POST récupère une donnée envoyées par la méthode POST
if ( $name == "") {
	if ( empty ($_POST ["name"]) == true)  $name = "";  else   $name = $_POST ["name"];
}

// Contrôle de la présence des paramètres
if ( $name == "") {
	$msg = "Erreur : données incomplètes ou incorrectes.";
}
else {
	// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
	include_once ('../modele/DAO.class.php');
	$dao = new DAO();

	// ! ou == false
	if ( ! $dao->existeUtilisateur($name) ) {
		$msg = "Erreur : nom d'utilisateur inexistant.";
	}
	else {
		// création d'un mot de passe aléatoire de 8 caractères
		$password = Outils::creerMdp();
		// modification du mot de passe
		$ok = $dao->modifierMdpUser($name, $password);
		if ( ! $ok ) {
			$msg = "Erreur : problème lors de la modification du mot de passe.";
		}
		else {
			// envoi d'un mail avec le nouveau mot de passe
			$unUtilisateur = $dao->getUtilisateur($name);
			$adrMail = $unUtilisateur->getEmail();
			$level = $dao->getNiveauUtilisateur($name, $password);
			
			$sujet = "Votre nouveau mot de passe";
			$contenuMail = "Voici vos données utilisateur, ainsi que votre nouveau mot de passe.\n\n";
			$contenuMail .= "Les données enregistrées sont :\n\n";
			$contenuMail .= "Votre nom : " . $name . "\n";
			$contenuMail .= "Votre nouveau mot de passe : " . $password . " (nous vous conseillons de le changer lors de la première connexion)\n";
			$contenuMail .= "Votre niveau d'accès : " . $level . "\n";
				
			$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
			if ( ! $ok ) {
				// l'envoi de mail a échoué
				$msg = "Modification du mot de passe effectué ; l'envoi du mail à l'utilisateur a rencontré un problème.";
			}
			else {
				// tout a bien fonctionné
				$msg = "Modification du mot de passe effectué ; Vous allez recevoir un mail avec votre nouveau mot de passe.";
			}
		}
	}
	// ferme la connexion à MySQL :
	unset($dao);
}

// création du flux XML en sortie
creerFluxXML ($msg);

// fin du programme (pour ne pas enchainer sur la fonction qui suit)
exit;


// création du flux XML en sortie
function creerFluxXML($msg)
{	// crée une instance de DOMdocument (DOM : Document Object Model)
	$doc = new DOMDocument();	

	// specifie la version et le type d'encodage
	$doc->version = '1.0';
	$doc->encoding = 'ISO-8859-1';
	
	// crée un commentaire et l'encode en ISO
	$elt_commentaire = $doc->createComment('Service web CreerUtilisateur - BTS SIO - Lycée De La Salle - Rennes');
	// place ce commentaire à la racine du document XML
	$doc->appendChild($elt_commentaire);
		
	// crée l'élément 'data' à la racine du document XML
	$elt_data = $doc->createElement('data');
	$doc->appendChild($elt_data);
	
	// place l'élément 'reponse' juste après l'élément 'data'
	$elt_reponse = $doc->createElement('reponse', $msg);
	$elt_data->appendChild($elt_reponse);
	
	// Mise en forme finale
	$doc->formatOutput = true;
	
	// renvoie le contenu XML
	echo $doc->saveXML();
	return;
}
?>