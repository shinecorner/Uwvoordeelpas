<script type="text/javascript">
$(document).ready(function() {
	$('.ui.checkbox').checkbox();
});
</script>

<div class="ui buttons fluid">
	<a href="{{ url('social/login/facebook') }}" target="_blank" id="facebookButton" class="ui facebook icon button">
	  <i class="facebook icon"></i>
	   Facebook
	</a>

	<a href="{{ url('social/login/google') }}" target="_blank" id="googleButton" class="ui icon google plus button">
	  <i class="google plus icon"></i>
	  Google+
	</a>
</div>

<button id="guestAccount" class="ui basic fluid button">
	<i class="user icon"></i> Login zonder account
</button>

<div class="ui horizontal divider">
    of
</div>

<div class="field">
	<label>E-mail</label>
	<input type="text" name="email">
</div>

<div class="field">
	<label>Wachtwoord <a href="#" class="ui recover password basic primary button">Wachtwoord vergeten?</a></label>
	<input type="password" name="password">
</div>

<div style="float: left; width: 200px;">
	<div class="ui checkbox">
	    <input type="checkbox" tabindex="0" value="1" name="remember" class="hidden">
	    <label>Onthoud mij</label>
	</div>
</div>

<div style="float: right;">
	<a href="#" id="registerButton3" data-redirect="">Nog geen lid?</a>
</div>

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
