<?php include "../inc/header.inc.php"; ?>

		<div class="wmw-dashboard col-xs-10 col-xs-offset-1 row">
			
			<div class="wmw-dashboard-sidebar col-xs-12 col-lg-2">

				<div class="sidebar-progress">
					Profil<br />
					100%
				</div>

				<div class="sidebar-notifications">
					<label for="wmw-profile-notifications">
						<i class="icon icon--bell"></i>
						Email<br />notifications
					</label>
					<div class="wmw-switchfield wmw-switchfield--small">
						<input type="checkbox" id="wmw-profile-notifications" />
						<label for="wmw-profile-notifications"></label>
					</div>
				</div>

				<div class="sidebar-text">
					<strong>YOUR DASHBOARD :</strong>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis molestie mi leo, eget varius arcu pellentesque eget. Phasellus pharetra elit in risus commodo commodo.
				</div>

			</div>

			<div class="wmw-dashboard-main col-xs-12 col-lg-10">

				<div class="wmw-profile">

					<div class="wmw-profile-header">
						<div class="wmw-profile-header-picture"><img src="../library/images/_tmp/michael-flanagan-large.jpg" /></div>
						<div class="wmw-profile-header-name">Mickael<br />FLANAGAN</div>
						<a href="#" class="wmw-profile-close"><i class="icon icon--cross"></i> Back to board</a>
					</div>

					<div class="wmw-profile-tabs row">
						<a href="#" class="active">General</a>
						<a href="#">Billing</a>
					</div>

					<div class="wmw-profile-content">
						<form action="/" method="post" class="row">

							<div class="col-xs-12 col-sm-5 row">
								<div class="wmw-bgfield col-xs-10">
									<label for="profile-email">Email</label>
									<input type="email" id="profile-email" name="profile-email" value="michaelflanagan@gmail.com" class="required" />
									<div class="wmw-bgfield-bg"><i class="icon icon--email"></i></div>
								</div>
								<div class="col-xs-1">
									<div class="wmw-switchfield">
										<input type="checkbox" id="profile-notifications" name="profile-notifications" />
										<label for="profile-notifications"></label>
									</div>
								</div>
								<div class="wmw-bgfield col-xs-10">
									<label for="profile-backup-email">Backup email</label>
									<input type="email" id="profile-backup-email" name="profile-backup-email" value="michaelflanagan@gmail.com" class="required" />
									<div class="wmw-bgfield-bg"><i class="icon icon--email"></i></div>
								</div>							
							</div>

							<div class="col-xs-12 col-sm-5 col-sm-offset-2">
								<div class="wmw-bgfield wmw-bgfield--persistent">
									<label for="profile-password">Nouveau mot de passe</label>
									<input type="password" id="profile-password" name="profile-password" />
									<div class="wmw-bgfield-bg"><i class="icon icon--locker"></i></div>
								</div>
								<div class="wmw-bgfield wmw-bgfield--persistent">
									<label for="profile-password-confirm">Verification</label>
									<input type="password" id="profile-password-confirm" name="profile-password-confirm" class="corresponding:profile-password" />
									<div class="wmw-bgfield-bg"><i class="icon icon--locker"></i></div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-5 row">
								<div class="wmw-bgfield col-xs-10">
									<label for="profile-phone">Phone number</label>
									<input type="text" id="profile-phone" name="profile-phone" value="(415) 616-4906" class="required" />
									<div class="wmw-bgfield-bg"><i class="icon icon--phone"></i></div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-5 col-sm-offset-2">
								<div class="wmw-bgfield">
									<label for="profile-languages">Languages</label>
									<input type="text" id="profile-languages" name="profile-languages" value="US, FR, ESP" class="required" />
									<div class="wmw-bgfield-bg"><i class="icon icon--language"></i></div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-6">
								<div class="wmw-bgfield">
									<label for="profile-address">Address</label>
									<input type="text" id="profile-address" name="profile-address" value="88 Kearny Street, Suite 600 - 94108 San Francisco" class="required" />
									<div class="wmw-bgfield-bg"><i class="icon icon--address"></i></div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-5 col-sm-offset-1">
								<div class="wmw-bgfield">
									<label for="profile-country">Country</label>
									<select id="profile-country" name="profile-country" class="required">
										<option value="USA">USA</option>
									</select>
									<div class="wmw-bgfield-bg"><i class="icon icon--world"></i></div>
								</div>
							</div>

							<div class="col-xs-12 col-sm-5 row">
								<div class="wmw-bgfield col-xs-10">
									<label for="profile-company">Company</label>
									<input type="text" id="profile-company" name="profile-company" value="Accenture" class="required" />
									<div class="wmw-bgfield-bg"><i class="icon icon--company"></i></div>
								</div>
							</div>

							<div class="col-xs-12 row">

								<div class="col-xs-12 col-md-6 row">
									<div class="wmw-profile-subtitle col-xs-12"><span>Algorythm improvement</span></div>
									<div class="wmw-bgfield col-xs-10">
										<label for="profile-linkedin">LinkedIn Account</label>
										<input type="text" id="profile-linkedin" name="profile-linkedin" value="http://linkedin.com/in/mickael-flanagan-53697a59" />
										<div class="wmw-bgfield-bg"><i class="icon icon--linkedin-thin"></i></div>
									</div>
									<div class="wmw-bgfield col-xs-10">
										<label for="profile-resume">Upload Resume</label>
										<input type="text" id="profile-resume" name="profile-resume" value="cv_mickael-flanagan_2017.pdf" />
										<div class="wmw-bgfield-file-hover">Change my resume</div>
										<input type="file" name="profile-resume-file" />
										<div class="wmw-bgfield-bg"><i class="icon icon--document"></i></div>
									</div>
									<div class="col-xs-2">
										<a href="#" class="wmw-profile-seelink"><i class="icon icon--eye"></i> Voir</a>
									</div>
								</div>

								<div class="col-xs-12 col-md-5 col-md-offset-1">
									<div class="wmw-profile-subtitle"><span>Subscription</span></div>
									<div class="wmw-profile-subscription">
										<div class="wmw-profile-subscription-plan">
											<span class="plan-icon"><i class="icon icon--star"></i></span>
											<span class="plan-label">Advisor plan</span>
										</div>
										<div class="wmw-profile-subscription-info">
											<i class="icon icon--clock"></i> 1 an<br />
											<i class="icon icon--calendar"></i> Ends 09 15, 2017
										</div>
									</div>
								</div>

							</div>

							<div class="wmw-profile-bottom col-xs-12">
								<button class="wmw-button wmw-button--green wmw-button--small" type="submit">
									<i></i><span>Save changes</span><i></i>
								</button>
							</div>

						</form>
					</div>

				</div>

			</div> 

		</div>

<?php include "../inc/footer.inc.php"; ?>