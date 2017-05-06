<?php include "../inc/header-onboarding.inc.php"; ?>

		<form method="post" action="onboarding-4.php" id="wmw-form-ob-4" class="wmw-onboard col-xs-10 col-xs-offset-1 col-md-6 col-md-offset-3 col-xl-4 col-xl-offset-4">

			<div class="wmw-onboard-body">

				<h1>
					Kind of Mission
					<small>Select your mission</small>
				</h1>

				<div class="wmw-onboard-switches">

					<div class="wmw-onboard-switches-el" data-num="1">
						<label for="wmw-ob-mission-1">Advisory Strategic</label>
						<div class="wmw-switchfield">
							<input type="hidden" id="wmw-ob-mission-1-val" value="" />
							<input type="checkbox" id="wmw-ob-mission-1" name="wmw-ob-missions[]" value="advisory-strategic" class="requiredgroup:wmw-ob-missions">
							<label for="wmw-ob-mission-1"><span>add</span><span><a href="#">edit</a></span></label>
						</div>
					</div>
					<div class="wmw-onboard-switches-el" data-num="2">
						<label for="wmw-ob-mission-2">Advisory Execution</label>
						<div class="wmw-switchfield">
							<input type="hidden" id="wmw-ob-mission-2-val" value="" />
							<input type="checkbox" id="wmw-ob-mission-2" name="wmw-ob-missions[]" value="advisory-execution" class="requiredgroup:wmw-ob-missions">
							<label for="wmw-ob-mission-2"><span>add</span><span><a href="#">edit</a></span></label>
						</div>
					</div>
					<div class="wmw-onboard-switches-el" data-num="3">
						<label for="wmw-ob-mission-3">Advisory Organisation - Transformation</label>
						<div class="wmw-switchfield">
							<input type="hidden" id="wmw-ob-mission-3-val" value="" />
							<input type="checkbox" id="wmw-ob-mission-3" name="wmw-ob-missions[]" value="advisory-orga" class="requiredgroup:wmw-ob-missions">
							<label for="wmw-ob-mission-3"><span>add</span><span><a href="#">edit</a></span></label>
						</div>
					</div>
					<div class="wmw-onboard-switches-el" data-num="4">
						<label for="wmw-ob-mission-4">Advisory Business Reingeniering</label>
						<div class="wmw-switchfield">
							<input type="hidden" id="wmw-ob-mission-4-val" value="" />
							<input type="checkbox" id="wmw-ob-mission-4" name="wmw-ob-missions[]" value="advisory-business" class="requiredgroup:wmw-ob-missions">
							<label for="wmw-ob-mission-4"><span>add</span><span><a href="#">edit</a></span></label>
						</div>
					</div>
					<div class="wmw-onboard-switches-el" data-num="5">
						<label for="wmw-ob-mission-5">Systems Information CSP</label>
						<div class="wmw-switchfield">
							<input type="hidden" id="wmw-ob-mission-5-val" value="" />
							<input type="checkbox" id="wmw-ob-mission-5" name="wmw-ob-missions[]" value="sysinfo-csp" class="requiredgroup:wmw-ob-missions">
							<label for="wmw-ob-mission-5"><span>add</span><span><a href="#">edit</a></span></label>
						</div>
					</div>
					<div class="wmw-onboard-switches-el" data-num="6">
						<label for="wmw-ob-mission-6">Systems Information MOA</label>
						<div class="wmw-switchfield">
							<input type="hidden" id="wmw-ob-mission-6-val" value="" />
							<input type="checkbox" id="wmw-ob-mission-6" name="wmw-ob-missions[]" value="sysinfo-moa" class="requiredgroup:wmw-ob-missions">
							<label for="wmw-ob-mission-6"><span>add</span><span><a href="#">edit</a></span></label>
						</div>
					</div>
					<div class="wmw-onboard-switches-el" data-num="7">
						<label for="wmw-ob-mission-7">Systems Information MOE</label>
						<div class="wmw-switchfield">
							<input type="hidden" id="wmw-ob-mission-7-val" value="" />
							<input type="checkbox" id="wmw-ob-mission-7" name="wmw-ob-missions[]" value="sysinfo-moe" class="requiredgroup:wmw-ob-missions">
							<label for="wmw-ob-mission-7"><span>add</span><span><a href="#">edit</a></span></label>
						</div>
					</div>

				</div>

				<div class="wmw-progressbar">
					<span class="active"></span>
					<span class="active"></span>
					<span class="active"></span>
					<span class="active"></span>
					<span></span>
				</div>

			</div>

			<div class="wmw-onboard-bottom">
				<a href="onboarding-2.php" class="wmw-button wmw-button--border"><i></i><span>Back</span><i></i></a>
				<button type="submit" class="wmw-button wmw-button--green"><i></i><span>Next</span><i></i></button>
			</div>
			
		</form>

		<form class="wmw-overlay" id="wmw-overlay-ob-4">
			<div class="wmw-overlay-inner col-xs-10 col-md-6 col-xl-4">
				<div class="wmw-overlay-title">Project Management Office</div>
				<div class="wmw-overlay-content">
					<div class="row">
						<div class="col-xs-4">
							<label for="company-small">
								<i class="icon icon--company-small"></i><br />
								Small companies
								<a href="#" class="wmw-tip">?</a>
							</label>
							<div class="wmw-switchfield">
								<input type="checkbox" id="company-small" name="company-small" class="requiredgroup:company-size">
								<label for="company-small"></label>
							</div>
						</div>
						<div class="col-xs-4">
							<label for="company-medium">
								<i class="icon icon--company-medium"></i><br />
								Medium companies
								<a href="#" class="wmw-tip">?</a>
							</label>
							<div class="wmw-switchfield">
								<input type="checkbox" id="company-medium" name="company-medium" class="requiredgroup:company-size">
								<label for="company-medium"></label>
							</div>
						</div>
						<div class="col-xs-4">
							<label for="company-large">
								<i class="icon icon--company-large"></i><br />
								Large companies
								<a href="#" class="wmw-tip">?</a>
							</label>
							<div class="wmw-switchfield">
								<input type="checkbox" id="company-large" name="company-large" class="requiredgroup:company-size">
								<label for="company-large"></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<label for="region-1">
								North America
							</label>
							<div class="wmw-switchfield">
								<input type="checkbox" id="region-1" name="company-regions-1" class="requiredgroup:company-region">
								<label for="region-1"></label>
							</div>
						</div>
						<div class="col-xs-3">
							<label for="region-2">
								South America
							</label>
							<div class="wmw-switchfield">
								<input type="checkbox" id="region-2" name="company-regions-2" class="requiredgroup:company-region">
								<label for="region-2"></label>
							</div>
						</div>
						<div class="col-xs-3">
							<label for="region-3">
								EMEA
							</label>
							<div class="wmw-switchfield">
								<input type="checkbox" id="region-3" name="company-regions-3" class="requiredgroup:company-region">
								<label for="region-3"></label>
							</div>
						</div>
						<div class="col-xs-3">
							<label for="region-4">
								Asia - Pacific
							</label>
							<div class="wmw-switchfield">
								<input type="checkbox" id="region-4" name="company-regions-4" class="requiredgroup:company-region">
								<label for="region-4"></label>
							</div>
						</div>
					</div>
					<div>
						<label for="duration">
							How many cumulated months ?
						</label><br />
						<div class="wmw-rangefield">
							<input type="range" id="duration" name="duration" min="1" max="60" step="1" value="12" />
							<div class="wmw-rangefield-value"><span>12</span> months</div>
						</div>
					</div>
					<div>
						<label for="fees">
							Average daily fees
						</label><br />
						<div class="wmw-rangefield">
							<input type="range" id="fees" name="fees" min="100" max="10000" step="100" value="1000" />
							<div class="wmw-rangefield-value"><span>1000</span> $</div>
						</div>
					</div>
					<div>
						<label for="lastmission">
							Was the last mission of this kind within the last 2 years ?
						</label>
						<div class="wmw-switchfield">
							<input type="checkbox" id="lastmission" name="lastmission">
							<label for="lastmission"><span>No</span><span>Yes</span></label>
						</div>
					</div>
				</div>
			</div><br />
			<div class="wmw-overlay-buttons">
				<button type="button" class="wmw-overlay-close wmw-button wmw-button--border"><i></i><span>Cancel</span><i></i></button>
				<button type="submit" class="wmw-button wmw-button--green"><i></i><span>Confirm</span><i></i></button>
			</div>
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