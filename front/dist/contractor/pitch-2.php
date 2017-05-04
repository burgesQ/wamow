<?php include "../inc/header.inc.php"; ?>

		<form method="post" action="pitch-3.php" class="wmw-onboard wmw-onboard--pitch col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">

			<div class="wmw-onboard-body">

				<h1>
					Caractéristiques de la mission
					<small>Dites-nous en plus !</small>
				</h1>

				<div class="wmw-sectionfield">
					<label class="wmw-label">Secteur d'activité</label>
				</div>

				<div class="wmw-onboard-businesses wmw-checklinks">
					<a href="#">
						<span>
							<i class="icon icon--business-manufacturing"></i>
							<strong>Manufacturing</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-sport"></i>
							<strong>Sport</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-hotel"></i>
							<strong>Hotel</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-food-beverage"></i>
							<strong>Food &amp; Beverage</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-construction"></i>
							<strong>Construction</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-media-telco-entertainment"></i>
							<strong>Media-Telco / Entertainment</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-finance"></i>
							<strong>Finance</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-retail"></i>
							<strong>Retail</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-tourisme"></i>
							<strong>Tourisme</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
					<a href="#">
						<span>
							<i class="icon icon--business-leisure"></i>
							<strong>Leisure</strong>
						</span>
					</a>
					<input type="checkbox" name="wmw-pitch-businesses[]" class="requiredgroup:wmw-pitch-businesses" />
				</div>

				<div class="wmw-fieldrow row">
					<label class="wmw-label wmw-label--topmobile col-xs-12 col-sm-5" for="wmw-pitch-domain">Domaine fonctionnel</label>
					<select class="required col-xs-12 col-md-6" id="wmw-pitch-domain">
						<option value="">Country of residence</option>
						<option value="france">France</option>
						<option value="germany">Germany</option>
						<option value="spain">Spain</option>
					</select>
				</div>
				
				<div class="wmw-fieldrow row">
					<label class="wmw-label wmw-label--topmobile col-xs-12 col-sm-5" for="wmw-pitch-budget">Budget estimé</label>
					<input class="required col-xs-12 col-md-6" type="text" id="wmw-pitch-budget" />
				</div>
				
				<div class="wmw-onboard-blocks row">
					<div class="wmw-onboard-blocks-el col-xs-6 col-md-3">
						<label for="wmw-pitch-region-1">North America</label>
						<div class="wmw-switchfield">
							<input type="radio" name="wmw-pitch-region" id="wmw-pitch-region-1" class="requiredgroup:wmw-pitch-region" />
							<label for="wmw-pitch-region-1"></label>
						</div>
					</div>
					<div class="wmw-onboard-blocks-el col-xs-6 col-md-3">
						<label for="wmw-pitch-region-2">South America</label>
						<div class="wmw-switchfield">
							<input type="radio" name="wmw-pitch-region" id="wmw-pitch-region-2" class="requiredgroup:wmw-pitch-region" />
							<label for="wmw-pitch-region-2"></label>
						</div>
					</div>
					<div class="wmw-onboard-blocks-el col-xs-6 col-md-3">
						<label for="wmw-pitch-region-3">EMEA</label>
						<div class="wmw-switchfield">
							<input type="radio" name="wmw-pitch-region" id="wmw-pitch-region-3" class="requiredgroup:wmw-pitch-region" />
							<label for="wmw-pitch-region-3"></label>
						</div>
					</div>
					<div class="wmw-onboard-blocks-el col-xs-6 col-md-3">
						<label for="wmw-pitch-region-4">Asia Pacific</label>
						<div class="wmw-switchfield">
							<input type="radio" name="wmw-pitch-region" id="wmw-pitch-region-4" class="requiredgroup:wmw-pitch-region" />
							<label for="wmw-pitch-region-4"></label>
						</div>
					</div>
				</div>

				<div class="wmw-fieldrow wmw-selectrow row">
					<label class="wmw-label wmw-label--topmobile col-xs-12 col-sm-5">Date limite de réponse au pitch</label>
					<div class="col-xs-12 col-md-6 row">
						<select class=" col-xs-4 required">
							<option value="">Jour</option>
						</select>
						<select class=" col-xs-4 required">
							<option value="">Mois</option>
						</select>
						<select class=" col-xs-4 required">
							<option value="">Année</option>
						</select>
					</div>
				</div>

				<div class="wmw-fieldrow wmw-selectrow row">
					<label class="wmw-label wmw-label--topmobile col-xs-12 col-sm-5">Début de mission</label>
					<div class="col-xs-12 col-md-6 row">
						<select class=" col-xs-4 required">
							<option value="">Jour</option>
						</select>
						<select class=" col-xs-4 required">
							<option value="">Mois</option>
						</select>
						<select class=" col-xs-4 required">
							<option value="">Année</option>
						</select>
					</div>
				</div>

				<div class="wmw-fieldrow wmw-selectrow row">
					<label class="wmw-label wmw-label--topmobile col-xs-12 col-sm-5">Fin de mission estimée</label>
					<div class="col-xs-12 col-md-6 row">
						<select class=" col-xs-4 required">
							<option value="">Jour</option>
						</select>
						<select class=" col-xs-4 required">
							<option value="">Mois</option>
						</select>
						<select class=" col-xs-4 required">
							<option value="">Année</option>
						</select>
					</div>
				</div>

				<div class="wmw-onboard-blocks row">
					<div class="wmw-onboard-blocks-el col-xs-12 col-md-6">
						<label for="wmw-pitch-anonymous">Entreprise anonyme</label>
						<div class="wmw-switchfield">
							<input type="checkbox" id="wmw-pitch-anonymous" />
							<label for="wmw-pitch-anonymous"></label>
						</div>
					</div>
					<div class="wmw-onboard-blocks-el col-xs-12 col-md-6">
						<label for="wmw-pitch-onsite">Présence requise sur le site</label>
						<div class="wmw-switchfield">
							<input type="checkbox" id="wmw-pitch-onsite" />
							<label for="wmw-pitch-onsite"></label>
						</div>
					</div>
				</div>

				<select class="required">
					<option value="">Pays</option>
				</select>

				<div class="wmw-progressbar">
					<span class="active"></span>
					<span class="active"></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
				
			</div>

			<div class="wmw-onboard-bottom">
				<button type="submit" class="wmw-button wmw-button--black wmw-button--border wmw-onboard-bottom-left"><i></i><span>Finir + tard</span><i></i></button><br />
				<a href="pitch-1.php" class="wmw-button wmw-button--border"><i></i><span>Back</span><i></i></a>
				<button type="submit" class="wmw-button wmw-button--green"><i></i><span>Next</span><i></i></button>
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