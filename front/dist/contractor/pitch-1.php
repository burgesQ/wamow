<?php include "../inc/header.inc.php"; ?>

		<form method="post" action="pitch-2.php" class="wmw-onboard wmw-onboard--pitch col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">

			<div class="wmw-onboard-body">

				<h1>
					Le pitch
					<small>Commençons par le commencement</small>
				</h1>

				<div class="wmw-fieldrow row">
					<label for="wmw-pitch-title" class="wmw-label col-xs-6 col-xl-5">Titre du pitch</label>
					<input type="text" id="wmw-pitch-title" class="required col-xs-6 col-xl-7" />
				</div>

				<div class="wmw-fieldrow row">
					<label for="wmw-pitch-desc" class="wmw-label wmw-label--top col-xs-12 col-md-6 col-xl-5">Description du projet (500 mots)</label>
					<textarea id="wmw-pitch-desc" class="required"></textarea>
				</div>

				<div class="wmw-progressbar">
					<span class="active"></span>
					<span></span>
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