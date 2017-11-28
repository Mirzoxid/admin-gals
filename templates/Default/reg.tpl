<article class="box story">
	<div class="box_in">
		<h4 class="title h1">Custom registration</h4>
		<div class="addform">
		<form action="" method="post" name="reg">
			<ul class="ui-form">
				<li class="form-group combo">
					<div class="combo_field"><input placeholder="Name" type="text" maxlength="35" name="name" id="name" class="wide" required></div>
				</li>
				<li class="form-group combo">
					<div class="combo_field"><input placeholder="Email" type="email" maxlength="35" name="email" id="email" class="wide" required></div>
				</li>
				<li class="form-group combo">
					<div class="combo_field"><input placeholder="Password" type="password" maxlength="35" name="password1" id="password1" class="wide" required></div>
				</li>
				<li class="form-group combo">
					<div class="combo_field"><input placeholder="Confirm password" type="password" maxlength="35" name="password2" id="password2" class="wide" required></div>
				</li>
				<li class="form-group combo">
					<div class="combo_feld">
						[sec_code]
							<li class="form-group">
								<div class="c-captcha">
									{reg_code}
									<input placeholder="Повторите код" title="Введите код указанный на картинке" type="text" name="sec_code" id="sec_code" required>
								</div>
							</li>
						[/sec_code]
						[recaptcha]
							<li>{recaptcha}</li>
						[/recaptcha]
					</div>
				</li>
			</ul>
			<div class="form_submit">
				<button class="btn btn-big" type="submit" name="send_btn"><b>Register me!</b></button>
			</div>
		</form>
		</div>
	</div>
</article>