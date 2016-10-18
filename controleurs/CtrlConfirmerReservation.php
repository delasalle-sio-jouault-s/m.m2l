<?php
// Projet Réservations M2L - version web mobile
// fichier : controleurs/CtrlCreerUtilisateur.php
// Rôle : traiter la demande de création d'un nouvel utilisateur
// Création : 21/10/2015 par JM CARTRON
// Mise à jour : 2/6/2016 par JM CARTRON
	
if (isset ($_POST['txtReservation']) == '') {
	// si les données sont incorrectes ou incomplètes, réaffichage de la vue de suppression avec un message explicatif
	$message = 'Données incomplètes ou incorrectes !';
	$typeMessage = 'avertissement';
	$themeFooter = $themeProbleme;
	include_once ('vues/VueConfirmerReservation.php');
}
else {
	// connexion du serveur web à la base MySQL
	include_once ('modele/DAO.class.php');
	$dao = new DAO();
		
	if ( !$dao->estLeCreateur($nom, $_POST ["txtReservation"])) {
		// si le nom existe déjà, réaffichage de la vue
		$message = "Vous n'êtes pas le créateur de cette réservation !";
		$typeMessage = 'avertissement';
		$themeFooter = $themeProbleme;
		include_once ('vues/VueConfirmerReservation.php');
	}
	else {
		 if ($dao->getReservation($_POST['txtReservation'])->getStatus() == 0)
		{
			// si la confirmation a déjà été effectuée					
			$message = "Cette réservation a déjà été confirmée !";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueConfirmerReservation.php');
		}
		
		else if ($dao->getReservation($_POST['txtReservation'])->getStart_time() < time())
		{
			// si la confirmation a déjà été effectuée					
			$message = "Cette réservation est dépassée !";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueConfirmerReservation.php');
		}
		else {
			$dao->confirmerReservation($_POST['txtReservation']);
			// envoi d'un mail de confirmation de l'enregistrement
			$unUtilisateur = $dao->getUtilisateur($nom);
			$adrMail = $unUtilisateur->getEmail();
			$sujet = "Confirmation de votre réservation dans le système de réservation de M2L";
			$contenuMail = "L'administrateur du système de réservations de la M2L vient de confirmer votre réservation.\n\n";
			$contenuMail .= "Les données enregistrées sont :\n\n";
			$contenuMail .= "Votre réservation : " . $_POST['txtReservation'] . "\n";
				
			$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
			if ( ! $ok ) {
				// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
				$message = "Enregistrement effectué.<br>L'envoi du mail à l'utilisateur a rencontré un problème !";
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueConfirmerReservation.php');
			}
			else {
				// tout a fonctionné
				$message = "Enregistrement effectué.<br>Un mail va être envoyé à l'utilisateur !";
				$typeMessage = 'information';
				$themeFooter = $themeNormal;
				include_once ('vues/VueConfirmerReservation.php');
			}
		}
	}
	unset($dao);		// fermeture de la connexion à MySQL
}