<?php
// Projet Réservations M2L - version web mobile
// Fichier : controleurs/CtrlChangerDeMdp.php
// Rôle : traiter la demande de changement de mot de passe
// Création : 08/11/2016 par Thibault PAQUET
// Mise à jour :  08/11/2016 par Thibault PAQUET

if ( ! isset ($_POST ["txtNouveauMdp"]) && ! isset ($_POST ["txtConfirmerMdp"])) {
	// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
	$name = '';
	$NouveauMdp = '';
	$ConfirmerMdp = '';
	$message = '';
	$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
	$themeFooter = $themeNormal;
	include_once ('vues/VueChangerDeMdp.php');
}
else {
	$name = $_SESSION['nom'];
	
	// récupération des données postées
	if ( empty ($_POST ["txtNouveauMdp"]) == true)  $NouveauMdp = "";  else   $NouveauMdp = $_POST ["txtNouveauMdp"];
	if ( empty ($_POST ["txtConfirmerMdp"]) == true)  $ConfirmerMdp = "";  else   $ConfirmerMdp = $_POST ["txtConfirmerMdp"];
	
	// inclusion de la classe Outils pour utiliser les méthodes statiques estUneAdrMailValide et creerMdp
	include_once ('modele/Outils.class.php');
	
	if ($NouveauMdp == '' || $ConfirmerMdp == '') {
		// si les données sont incorrectes ou incomplètes, réaffichage de la vue de demande avec un message explicatif
		$message = 'Données incomplètes ou incorrectes !';
		$typeMessage = 'avertissement';
		$themeFooter = $themeProbleme;
		include_once ('vues/VueChangerDeMdp.php');
	}
	else {
		// inclusion de la classe Outils pour utiliser les méthodes statiques creerMdp
		include_once ('modele/Outils.class.php');
		// connexion du serveur web à la base MySQL
		include_once ('modele/DAO.class.php');
		$dao = new DAO();
			
		if ( $NouveauMdp != $ConfirmerMdp ) {
			// si le nom n'existe pas, réaffichage de la vue
			$message = "•	Le nouveau mot de passe et<br>sa confirmation sont différents !";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueChangerDeMdp.php');
		}
		else {
			// modification du mot de passe
			$ok = $dao->modifierMdpUser($name, $NouveauMdp);
			if ( ! $ok ) {
				// si l'enregistrement a échoué, réaffichage de la vue avec un message explicatif					
				$message = "Problème lors de la modification du nouveau mot de passe !";
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueChangerDeMdp.php');
			}
			else {
				// envoi d'un mail avec le nouveau mot de passe
				$unUtilisateur = $dao->getUtilisateur($name);
				$adrMail = $unUtilisateur->getEmail();
				
				$sujet = "Votre nouveau mot de passe";
				$contenuMail = "Voici vos données utilisateur, ainsi que votre nouveau mot de passe.\n\n";
				$contenuMail .= "Les données enregistrées sont :\n\n";
				$contenuMail .= "Votre nom : " . $name . "\n";
				$contenuMail .= "Votre nouveau mot de passe : " . $NouveauMdp;
					
				$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
				if ( ! $ok ) {
					// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
					$message = "Enregistrement effectué.<br>L'envoi du mail de confirmation a rencontré un problème. ";
					$typeMessage = 'avertissement';
					$themeFooter = $themeProbleme;
					include_once ('vues/VueChangerDeMdp.php');
				}
				else {
					// tout a fonctionné
					$message = "Enregistrement effectué.<br>Vous allez recevoir un mail de confirmation.";
					$typeMessage = 'information';
					$themeFooter = $themeNormal;
					include_once ('vues/VueChangerDeMdp.php');
				}
			}
		}	
		unset($dao);		// fermeture de la connexion à MySQL
	}
}
