<?php
// Projet Réservations M2L - version web mobile
// fichier : controleurs/CtrlDemanderMdp.php
// Rôle : traiter la demande d'envoie d'un nouveau mot de passe
// Création : 18/10/2016 par Patrick MOREL
// Mise à jour : 18/10/2016 par Patrick MOREL

if ( ! isset ($_POST ["txtName"]) ) {
	// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
	$name = '';
	$message = '';
	$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
	$themeFooter = $themeNormal;
	include_once ('vues/VueDemanderMdp.php');
}
else {
	// récupération des données postées
	if ( empty ($_POST ["txtName"]) == true)  $name = "";  else   $name = $_POST ["txtName"];
	
	// inclusion de la classe Outils pour utiliser les méthodes statiques estUneAdrMailValide et creerMdp
	include_once ('modele/Outils.class.php');
	
	if ($name == '') {
		// si les données sont incorrectes ou incomplètes, réaffichage de la vue de demande avec un message explicatif
		$message = 'Données incomplètes ou incorrectes !';
		$typeMessage = 'avertissement';
		$themeFooter = $themeProbleme;
		include_once ('vues/VueDemanderMdp.php');
	}
	else {
		// connexion du serveur web à la base MySQL
		include_once ('modele/DAO.class.php');
		$dao = new DAO();
			
		if ( ! $dao->existeUtilisateur($name) ) {
			// si le nom n'existe pas, réaffichage de la vue
			$message = "Nom d'utilisateur inexistant !";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueDemanderMdp.php');
		}
		else{
			$unUtilisateur = $dao->getUtilisateur($name);
			$adrMail = $unUtilisateur->getEmail();
				
			$ok = $dao->supprimerUtilisateur($name);
				
			if ( !$ok ){
				$message = "Problème lors de la suppression de l'utilisateur !";
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueDemanderMdp.php');
			}
			else {
				// envoi d'un mail de confirmation de la suppression
				$sujet = "Suppression de votre compte dans le système de réservation de M2L";
				$contenuMail = "L'administrateur du système de réservations de la M2L vient de supprimer votre compte utilisateur.\n\n";
					
				$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
				if ( ! $ok ) {
					// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
					$message = "Echec lors de l'envoi du mail !";
					$typeMessage = 'avertissement';
					$themeFooter = $themeProbleme;
					include_once ('vues/VueDemanderMdp.php');
				}
				else {
					// tout a fonctionné
					$message = "Vous allez recevoir un mail<br>avec votre nouveau mot de passe";
					$typeMessage = 'information';
					$themeFooter = $themeNormal;
					include_once ('vues/VueDemanderMdp.php');
				}
			}
		}	
		unset($dao);		// fermeture de la connexion à MySQL
	}
}
