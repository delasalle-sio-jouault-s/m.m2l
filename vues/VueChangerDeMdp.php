<?php
	// Projet Réservations M2L - version web mobile
	// Fichier : vues/VueDemanderMdp.php
	// Rôle : traiter la demande de changement de mot de passe
	// cette vue est appelée par le contôleur controleurs/CtrlDemanderMdp.php
	// Création : 08/11/2016 par Thibault PAQUET
	// Mise à jour :  08/11/2016 par Thibault PAQUET
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
		
		<script>
			// version jQuery activée
			
			// associe une fonction à l'événement pageinit
			$(document).bind('pageinit', function() {
				<?php if ($typeMessage != '') { ?>
					// affiche la boîte de dialogue 'affichage_message'
					$.mobile.changePage('#affichage_message', {transition: "<?php echo $transition; ?>"});
				<?php } ?>
			} );

			// selon l'état de la case, le type de la zone de saisie est "text" ou "password"
			function afficherMdp() {
				// tester si la case est cochée
				if ( $("#caseAfficherMdp").is(":checked") ) {
					// la zone passe en <input type="text">
					$('#txtNouveauMdp').attr('type', 'text');
					$('#txtConfirmerMdp').attr('type', 'text');
				}
				else {
					// la zone passe en <input type="password">
					$('#txtNouveauMdp').attr('type', 'password');
					$('#txtConfirmerMdp').attr('type', 'password');
				};
			}
			// fin de la version jQuery
		</script>
	</head>
	
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4>M2L-GRR</h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Changer mot de passe</h4>
				<form action="index.php?action=ChangerDeMdp" method="post" data-ajax="false">
					<div data-role="fieldcontain" class="ui-label">
						<label for="txtNouveauMdp">Nouveau mot de passe :</label>
						<input type="password" name="txtNouveauMdp" id="txtNouveauMdp" required placeholder="Mon nouveau mot de passe">
					</div>
					<div data-role="fieldcontain" class="ui-label">
						<label for="txtConfirmerMdp">Confirmer nouveau mot de passe :</label>
						<input type="password" name="txtConfirmerMdp" id="txtConfirmerMdp" required placeholder="Confirmation de mon nouveau mot de passe">
					</div>
					<div data-role="fieldcontain" data-type="horizontal" class="ui-hide-label">
						<label for="caseAfficherMdp">Afficher le mot de passe en clair</label>
						<input type="checkbox" name="caseAfficherMdp" id="caseAfficherMdp" onclick="afficherMdp();" data-mini="true" <?php if ($afficherMdp == 'on') echo 'checked'; ?>>
					</div>
					<div data-role="fieldcontain">
						<input type="submit" name="btnNewMdp" id="btnNewMdp" value="Envoyer les données" data-mini="true">
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