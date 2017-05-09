<?php include "../inc/header.inc.php"; ?>

		<form method="post" action="pitch-5.php" class="wmw-onboard wmw-onboard--pitch col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">

			<div class="wmw-onboard-body">

				<h1>
					Final step
					<small>Dernière étape et nous y sommes !</small>
				</h1>

				<p class="wmw-onboard-mentions">
					Consultants.wantmore.work est une marque de BestAdvizor SAS, Société au capital de 51.000 euros - RCS Paris 
					815 362 736 - Siège social : 116 Av Felix Faure 75015 Paris Bureaux : 18 avenue Franklin D. Roosevelt 75008 Paris
				</p>

				<p class="wmw-onboard-paragraph">
					Je soussigné <input type="text" class="required" placeholder="Nom" /> <input type="text" class="required" placeholder="Prénom" /><br />
					déclare être habilité à contractualiser pour la société <input type="text" class="required" placeholder="Société" /><br />
					autorise BestAdvizor SAS à :
				</p>

				<div class="wmw-onboard-blocks row">
					<div class="wmw-onboard-blocks-el col-xs-12 col-md-6">
						<label for="wmw-pitch-optin-1">diffuser le pitch ci-dessous rédigé à la communauté d'experts consultants.wantmore.work</label>
						<div class="wmw-switchfield">
							<input type="checkbox" id="wmw-pitch-optin-1" class="required vv-gparent" />
							<label for="wmw-pitch-optin-1"></label>
						</div>
					</div>
					<div class="wmw-onboard-blocks-el col-xs-12 col-md-6">
						<label for="wmw-pitch-optin-2">diffuser le nom de l'entreprise comme une référence</label>
						<div class="wmw-switchfield">
							<input type="checkbox" id="wmw-pitch-optin-2" class="required vv-gparent" />
							<label for="wmw-pitch-optin-2"></label>
						</div>
					</div>
				</div>

				<p class="wmw-onboard-paragraph">
					et voir déclaré comme commissaires aux comptes
				</p>

				<div class="wmw-fieldrow wmw-copyfield row">
					<input type="text" data-copy-nb="1" data-copy-name="wmw-ob-responsible" id="wmw-ob-responsible-1" name="languages[]" placeholder="Nom" class="wmw-autocomplete col-xs-10 col-md-5 required" />
					<a href="#" class="wmw-button-more">+</a>
				</div>

				<p class="wmw-onboard-mentions">
					BestAdvizor met en relation une expression de besoin (Pitch) - en accompagnant le demandeur dans sa 
					formulation - et des experts sur la base d'une proposition de solution. BestAdvizor n'est pas un cabinet de conseil et son 
					engagement se limite à cette mise en relation qualiﬁée. Sa responsabilité ne saurait être engagée sur le délivrable de la
					mission en lui même. Clause attributive de compétence : tous les litiges relatifs à l'application de la présente 
					seront de la compétence exclusive du Tribunal de Commerce de Paris.
				</p>

				<p class="wmw-onboard-mentions">
					La Société se réserve le droit de faire état des pitchs réalisés et des noms des clients, de les divulguer à titre de références.
				</p>

				<div class="wmw-progressbar">
					<span class="active"></span>
					<span class="active"></span>
					<span class="active"></span>
					<span class="active"></span>
					<span class="active"></span>
				</div>
				
			</div>

			<div class="wmw-onboard-bottom">
				<a href="pitch-4.php" class="wmw-button wmw-button--border"><i></i><span>Back</span><i></i></a>
				<button type="submit" class="wmw-button wmw-button--green"><i></i><span>Diffuser le pitch</span><i></i></button>
			</div>

		</form>

		<div class="wmw-onboard-text col-md-2">
			<p>
				85% OF PITCHES ARE FINDING EXPERTS 
				WITHIN 15 DAYS.
			</p>
			<p>
				Having a good matching profile will invrease
				your succès !So fill in your profile details
				with attention.<br />
				Be aware that the User Agreement of www.
				consultants.wantmore.work require a ID 
				background check.
			</p>
		</div>

<?php include "../inc/footer.inc.php"; ?>