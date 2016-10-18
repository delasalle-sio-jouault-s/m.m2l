<?php
// Projet Réservations M2L - version web mobile
// fichier : vues/VueAnnulerReservation.php
// Rôle : visualiser la demande d'annulation d'une reservation
// cette vue est appelée par le contôleur controleurs/CtrlAnnulerReservation.php
// Création : 18/10/2016 par Thibault PAQUET 
// Mise à jour : 18/10/2016 par Thibault PAQUET 
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
		
		<script>
			// associe une fonction à l'événement pageinit
			$(document).bind('pageinit', function() {
				<?php if ($typeMessage != '') { ?>
					// affiche la boîte de dialogue 'affichage_message'
					$.mobile.changePage('#affichage_message', {transition: "<?php echo $transition; ?>"});
				<?php } ?>
			} );
		</script>
	</head>
	
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4>M2L-GRR</h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Annuler une réservation</h4>
				<form action="index.php?action=AnnulerReservation" method="post" data-ajax="false">
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="txtAnnulerReservation">Annuler Reservation :</label>
						<input type="number" name="txtAnnulerReservation" id="txtAnnulerReservation" required placeholder="Entrez le numéro de la réservation">
					</div>
					<div data-role="fieldcontain">
						<input type="submit" name="btnAnnulerReservation" id="btnAnnulerReservation" value="Annuler Réservation" data-mini="true">
					</div>
				</form>
			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4>Suivi des réservations de salles<br>Maison des ligues de Lorraine (M2L)</h4>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
		
	</body>
</html>