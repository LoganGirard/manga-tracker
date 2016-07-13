<div class="row" style="margin-top:20px">
	<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<form action="<?=base_url('user/login')?>" method="post" accept-charset="utf-8" role="form">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">

			<fieldset>
				<h2>Please Sign In</h2>

				<hr class="colorgraph">

				<div class="form-group">
					<?=form_input($form_identity)?>
				</div>
				<div class="form-group">
					<?=form_input($form_password)?>
				</div>
				<span><?=print_r($notices, TRUE)?></span>
				<span class="button-checkbox">
					<button type="button" class="btn" data-color="info">Remember Me</button>
					<?=form_checkbox($form_remember)?>
					<a href="<?=base_url('user/forgot_password')?>" class="btn btn-link pull-right">Forgot Password?</a>
				</span>

				<hr class="colorgraph">

				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<?=form_submit($form_submit);?>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<a href="<?=base_url('user/signup')?>" class="btn btn-lg btn-primary btn-block">Create an account</a>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>