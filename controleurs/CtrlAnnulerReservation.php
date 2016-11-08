<?php
// Projet Réservations M2L - version web mobile
// fichier : controleurs/CtrlAnnuler.php
// Rôle : traiter la demande d'annulation d'une reservation
// Création : 18/10/2016 par Thibault PAQUET 
// Mise à jour : 18/10/2016 par Thibault PAQUET 

	if (isset($_POST["txtAnnulerReservation"]) == '')
	{
			// si les données sont incorrectes ou incomplètes, réaffichage de la vue de suppression avec un message explicatif
		$message = 'Données incomplètes ou incorrectes !';
		$typeMessage = 'avertissement';
		$themeFooter = $themeProbleme;
		include_once ('vues/VueAnnulerReservation.php');
	}
	else 
	{
		// connexion du serveur web à la base MySQL
		include_once ('modele/DAO.class.php');
		$dao = new DAO();
			
		if ($dao->existeReservation($_POST["txtAnnulerReservation"]) == false)
		{
			$message = "Numéro de réservation inexistant !";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueAnnulerReservation.php');
		}
		else if ($dao->estLeCreateur($_SESSION['nom'], $_POST["txtAnnulerReservation"]) == false) 
		{
			// si l'utilisateur n'est pas le créateur
			$message = "Vous n'êtes pas l'auteur de cette réservation !";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueAnnulerReservation.php');
		}
		else if (($dao->getReservation($_POST["txtAnnulerReservation"])->getStart_time()) < time())
		{
			// si l'enregistrement a échoué, réaffichage de la vue avec un message explicatif					
			$message = "Cette réservation est déjà passée !";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueAnnulerReservation.php');
		}
		else  
		{
			$dao->annulerReservation($_POST["txtAnnulerReservation"]);
			
			// envoi d'un mail de confirmation de l'enregistrement
			$adrMail = $dao->getUtilisateur($_SESSION["nom"])->getEmail();
			$sujet = "Annulation dans le système de réservation de M2L";
			$contenuMail = "L'administrateur du système de réservations de la M2L vient d'annuler votre réservation.\n\n";
			$contenuMail .= "La réservation annulée est : ".$_POST["txtAnnulerReservation"];
				
			$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
			if ( ! $ok ) 
			{
				// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
				$message = "Enregistrement effectué.<br>L'envoi du mail de confirmation a rencontré un problème. ";
				$typeMessage = 'avertissement';
				$themeFooter = $themeProbleme;
				include_once ('vues/VueAnnulerReservation.php');
			}
			else 
			{
				// tout a fonctionné
				$message = "Enregistrement effectué.<br>Vous allez recevoir un mail de confirmation.";
				$typeMessage = 'information';
				$themeFooter = $themeNormal;
				include_once ('vues/VueAnnulerReservation.php');
			}
		}
		unset($dao);		// fermeture de la connexion à MySQL
	}