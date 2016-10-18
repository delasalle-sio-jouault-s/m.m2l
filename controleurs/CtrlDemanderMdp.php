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
		// inclusion de la classe Outils pour utiliser les méthodes statiques creerMdp
		include_once ('modele/Outils.class.php');
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
		else {
			// création d'un mot de passe aléatoire de 8 caractères
			$password = Outils::creerMdp();
			// modification du mot de passe
			$ok = $dao->modifierMdpUser($name, $password);
			if ( ! $ok ) {
				// si l'enregistrement a échoué, réaffichage de la vue avec un message explicatif					
				$message = "Problème lors de la modification du nouveau mot de passe !";
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueDemanderMdp.php');
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
