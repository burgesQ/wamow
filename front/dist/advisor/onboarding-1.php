<?php include "../inc/header-onboarding.inc.php"; ?>

		<form method="post" action="onboarding-2.php" class="wmw-onboard col-xs-10 col-xs-offset-1 col-md-6 col-md-offset-3 col-xl-4 col-xl-offset-4">

			<div class="wmw-onboard-body">

				<h1>
					<small>Get matched in just 5 minutes.</small>
					First, let's connect
				</h1>

				<button class="wmw-button">
					<i></i><span>Sign up with linkedin</span><i></i>
				</button>

				<div class="wmw-onboard-or">OR</div>

				<div class="wmw-fieldrow row">
					<input type="text" id="wmw-ob-firstname" name="wmw-ob-firstname" class="required col-xs-7" />
					<label class="wmw-label wmw-label--right col-xs-5" for="wmw-ob-firstname">First name</label>
				</div>

				<div class="wmw-fieldrow row">
					<input type="text" id="wmw-ob-lastname" name="wmw-ob-lastname" class="required col-xs-7" />
					<label class="wmw-label wmw-label--right col-xs-5" for="wmw-ob-lastname">Last name</label>
				</div>

				<div class="wmw-fieldrow row">
					<input type="email" id="wmw-ob-email" name="wmw-ob-email" class="required email col-xs-7" />
					<label class="wmw-label wmw-label--right col-xs-5" for="wmw-ob-email">E-mail</label>
				</div>

				<div class="wmw-fieldrow row">
					<select id="wmw-ob-country" name="wmw-ob-country" class="col-xs-12 col-sm-6 col-md-5 required">
						<option value="">Country of residence</option>
						<option value="france">France</option>
						<option value="germany">Germany</option>
						<option value="spain">Spain</option>
					</select>
				</div>

				<div class="wmw-fieldrow">
					<div class="wmw-switchfield">
						<input type="checkbox" id="wmw-ob-mobile" name="wmw-ob-mobile" />
						<label for="wmw-ob-mobile">I'm mobile</label>
					</div>
				</div>

				<div class="wmw-fieldrow wmw-copyfield row">
					<label for="wmw-ob-language-1" class="wmw-label wmw-label--topmobile col-xs-10 col-sm-5">Langue de travail</label>
					<select data-copy-nb="1" data-copy-name="wmw-ob-language" id="wmw-ob-language-1" name="languages[]" class="col-xs-10 col-md-5 required">
						<option value="fr">Français</option>
						<option value="en">Anglais</option>
						<option value="es">Espagnol</option>
					</select>
					<a href="#" class="wmw-button-more">+</a>
				</div>

				<div class="wmw-uploadfield">
					<input type="file" id="wmw-ob-resume" name="wmw-ob-resume" />
					<button type="button" class="wmw-button wmw-button--green"><i></i><span>Upload your cv</span><i></i></button>
					<div class="wmw-uploadfield-val"></div>
				</div>

			</div>

			<button type="submit" class="wmw-button wmw-button--green wmw-button--border"><i></i><span>Next</span><i></i></button>

		</form>

		<div class="wmw-onboard-text col-md-3 col-xl-2">
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