@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

   	<div class="ui top attached tabular menu">
		<a class="active item" data-tab="website">Website</a>
		<a class=" item" data-tab="api">API gegevens</a>
		<a class="item" data-tab="cronjobs">Cronjobs</a>
		<a class="item" data-tab="discount">Afbeeldingen</a>
		<a class="item" data-tab="eetnu">EetNU</a>
		<a class="item" data-tab="invoices">Opstartkosten</a>
	</div>

	<div class="ui bottom active attached tab segment" data-tab="website">
		<?php echo Form::open(array('url' => 'admin/settings/website', 'method' => 'post', 'class' => 'ui form')) ?>
		<div class="field">
			<label>Facebook pagina</label>
			<?php echo Form::number('facebook', isset($websiteSettings['facebook']) ? $websiteSettings['facebook'] : ''); ?>
		</div>

		<div class="field">
			<label>Bronnen (Elke nieuwe bron op een nieuwe regel)</label>
			<?php echo Form::textarea('source', isset($websiteSettings['source']) ? $websiteSettings['source'] : ''); ?>
		</div>	

		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
		<?php echo Form::close() ?>
	</div>

	<div class="ui bottom attached tab segment" data-tab="api">
	   	<?php echo Form::open(array('method' => 'post', 'class' => 'ui form')) ?>
	   	<h4>Callcenter</h4>

		<div class="field">
			<div class="ui slider checkbox">
					<input 
					type="checkbox" 
					name="callcenter_reminder_status"
					{{ isset($apiSettings['callcenter_reminder_status']) ? 'checked="checked"' : '' }}>
				<label>Herinnering sturen na 48 uur</label>
			</div>
		</div>

		<div class=" field">
 			<label>Tijd in uren</label>
 			Om de hoeveel tijd moet er een herinnering gestuurd worden (uren):
			<?php echo Form::text('callcenter_reminder', isset($apiSettings['callcenter_reminder']) ? $apiSettings['callcenter_reminder'] : 48); ?>
		</div>

		<h4>Affilinet</h4>
		<div class="fields">
			<div class="two wide field">
			   	<label>Uitvoeren</label>
			   	<a href="{{ url('admin/settings/run/affilinet') }}" class="ui icon button"><i class="refresh icon"></i></a>
			</div>

			<div class="four wide field">
			   	<label>Gebruikersnaam</label>
			    <?php echo Form::text('affilinet_name', isset($apiSettings['affilinet_name']) ? $apiSettings['affilinet_name'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Wachtwoord</label>
			    <?php echo Form::text('affilinet_pw', isset($apiSettings['affilinet_pw']) ? $apiSettings['affilinet_pw'] : ''); ?>
			</div>
		</div>
		
	   	<h4>Daisycon</h4>

		<div class="fields">
			<div class="two wide field">
			   	<label>Uitvoeren</label>
			   	<a href="{{ url('admin/settings/run/daisycon') }}" class="ui icon button"><i class="refresh icon"></i></a>
			</div>

			<div class="four wide field">
			   	<label>Gebruikersnaam</label>
			    <?php echo Form::text('daisycon_name', isset($apiSettings['daisycon_name']) ? $apiSettings['daisycon_name'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Wachtwoord</label>
			    <?php echo Form::text('daisycon_pw', isset($apiSettings['daisycon_pw']) ? $apiSettings['daisycon_pw'] : ''); ?>
			</div>
		</div>
		
	   	<h4>Tradetracker</h4>

		<div class="fields">
			<div class="two wide field">
			   	<label>Uitvoeren</label>
			   	<a href="{{ url('admin/settings/run/tradetracker') }}" class="ui icon button"><i class="refresh icon"></i></a>
			</div>

			<div class="four wide field">
			   	<label>Gebruikersnaam</label>
			    <?php echo Form::text('tradetracker_name', isset($apiSettings['tradetracker_name']) ? $apiSettings['tradetracker_name'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Wachtwoord</label>
			    <?php echo Form::text('tradetracker_pw', isset($apiSettings['tradetracker_pw']) ? $apiSettings['tradetracker_pw'] : ''); ?>
			</div>
		</div>
		
	   	<h4>Tradedoubler (transacties)</h4>

		<div class="fields">
			<div class="two wide field">
			   	<label>Uitvoeren</label>
			   	<a href="{{ url('admin/settings/run/tradedoubler') }}" class="ui icon button"><i class="refresh icon"></i></a>
			</div>

			<div class="four wide field">
			   	<label>Gebruikersnaam</label>
			    <?php echo Form::text('tradedoubler_name', isset($apiSettings['tradedoubler_name']) ? $apiSettings['tradedoubler_name'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Wachtwoord</label>
			    <?php echo Form::text('tradedoubler_pw', isset($apiSettings['tradedoubler_pw']) ? $apiSettings['tradedoubler_pw'] : ''); ?>
			</div>
		</div>

	   	<h4>Zanox</h4>
		<div class="fields">
			<div class="two wide field">
			   	<label>Uitvoeren</label>
			   	<a href="{{ url('admin/settings/run/zanox') }}" class="ui icon button"><i class="refresh icon"></i></a>
			</div>

			<div class="four wide field">
			   	<label>ConnectID</label>
			    <?php echo Form::text('zanox_name', isset($apiSettings['zanox_name']) ? $apiSettings['zanox_name'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Secret key</label>
			    <?php echo Form::text('zanox_pw', isset($apiSettings['zanox_pw']) ? $apiSettings['zanox_pw'] : ''); ?>
			</div>
		</div>

	   	<h4>Hotspot</h4>
		<div class="fields">
			<div class="two wide field">
			   	<label>Uitvoeren</label>
			   	<a href="{{ url('admin/settings/run/hotspot') }}" class="ui icon button"><i class="refresh icon"></i></a>
			</div>

			<div class="twelve wide field">
			    <label>Wachtwoord (key)</label>
			    <?php echo Form::text('hotspot_pw', isset($apiSettings['hotspot_pw']) ? $apiSettings['hotspot_pw'] : ''); ?>
			</div>
		</div>

		<br /><br />

		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
		<?php echo Form::close() ?>
	</div>

	<div class="ui bottom attached tab segment" data-tab="cronjobs">   	
		<?php echo Form::open(array('url' => 'admin/settings/cronjobs', 'method' => 'post', 'class' => 'ui form')) ?>
   		<h4>Affiliaties</h4>
		
		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
				  	<input 
					  	type="checkbox" 
				  		name="affilinet_affiliate"
				  		{{ isset($cronjobSettings['affilinet_affiliate']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg nieuwe programma's toe van Affilinet.</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
				  	<input 
				  		type="checkbox" 
				  		name="tradetracker_affiliate"
				  		{{ isset($cronjobSettings['tradetracker_affiliate']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg nieuwe programma's toe van Tradetracker.</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="zanox_affiliate"
				  		{{ isset($cronjobSettings['zanox_affiliate']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg nieuwe programma's toe van Zanox.</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="daisycon_affiliate"
				  		{{ isset($cronjobSettings['daisycon_affiliate']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg nieuwe programma's toe van Daisycon.</label>
				</div>
			</div>
		</div>

	   	<h4>Transacties</h4>
		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
				  	<input 
					  	type="checkbox" 
				  		name="affilinet_transaction"
				  		{{ isset($cronjobSettings['affilinet_transaction']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg transacties toe van Affilinet.</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
				  	<input 
				  		type="checkbox" 
				  		name="tradetracker_transaction"
				  		{{ isset($cronjobSettings['tradetracker_transaction']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg transacties toe van Tradetracker.</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="zanox_transaction"
				  		{{ isset($cronjobSettings['zanox_transaction']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg transacties toe van Zanox.</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="daisycon_transaction"
				  		{{ isset($cronjobSettings['daisycon_transaction']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg transacties toe van Daisycon.</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="tradedoubler_transaction"
				  		{{ isset($cronjobSettings['tradedoubler_transaction']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg transacties toe van TradeDoubler.</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="que_transaction"
				  		{{ isset($cronjobSettings['que_transaction']) ? 'checked="checked"' : '' }}>
				  	<label>Keurt alle transacties (geaccepteerd, afgekeurd of geweigerd)</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="expired_transaction"
				  		{{ isset($cronjobSettings['expired_transaction']) ? 'checked="checked"' : '' }}>
				  	<label>Laat transacties verlopen op de 90e dag</label>
				</div>
			</div>
		</div>

	   	<h4>Facturen</h4>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="mollie_invoice"
				  		{{ isset($cronjobSettings['mollie_invoice']) ? 'checked="checked"' : '' }}>
				  	<label>Stuur alle eigenaren een bericht wanneer hun geincasseerde factuur is mislukt. (incasso)</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="product_invoice"
				  		{{ isset($cronjobSettings['product_invoice']) ? 'checked="checked"' : '' }}>
				  	<label>Stuur facturen naar klanten (Producten)</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="reservation_invoice"
				  		{{ isset($cronjobSettings['reservation_invoice']) ? 'checked="checked"' : '' }}>
				  	<label>Stuur facturen naar klanten (Reservering)</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="debit_invoice"
				  		{{ isset($cronjobSettings['debit_invoice']) ? 'checked="checked"' : '' }}>
				  	<label>Maak XML aan voor incasso</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="reminder_invoice"
				  		{{ isset($cronjobSettings['reminder_invoice']) ? 'checked="checked"' : '' }}>
				  	<label>Stuur betaal herinnering</label>
				</div>
			</div>
		</div>

	   	<h4>Reserveringen</h4>
		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="thirdparty_mail"
				  		{{ isset($cronjobSettings['thirdparty_mail']) ? 'checked="checked"' : '' }}>
				  	<label>Stuur een mail naar alle klanten van Couverts, SeatMe en EetNU <span class="ui red small header color">nieuw</span></label>
				</div>
			</div>
		</div>
		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="reminder_reservation"
				  		{{ isset($cronjobSettings['reminder_reservation']) ? 'checked="checked"' : '' }}>
				  	<label>Stuur klanten een herinnering voor reservering.</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="reminder_review"
				  		{{ isset($cronjobSettings['reminder_review']) ? 'checked="checked"' : '' }}>
				  	<label>Stuur klanten een herinnering om een recensie te plaatsen.</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="today_reservation"
				  		{{ isset($cronjobSettings['today_reservation']) ? 'checked="checked"' : '' }}>
				  	<label>Stuur de admin reserveringen na sluitingstijd van vandaag</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="pay_reservation"
				  		{{ isset($cronjobSettings['pay_reservation']) ? 'checked="checked"' : '' }}>
				  	<label>Zet reserveringen als betaald na reservering datum/tijd</label>
				</div>
			</div>
		</div>

	   	<h4>Overige</h4>
		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="wifi_guest"
				  		{{ isset($cronjobSettings['wifi_guest']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg gasten toe via wifi hotspot</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="sitemap_other"
				  		{{ isset($cronjobSettings['sitemap_other']) ? 'checked="checked"' : '' }}>
				  	<label>Maak een sitemap aan</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="expired_barcode"
				  		{{ isset($cronjobSettings['expired_barcode']) ? 'checked="checked"' : '' }}>
				  	<label>Verwijder verlopen barcodes na een jaar</label>
				</div>
			</div>

			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="validate_payment"
				  		{{ isset($cronjobSettings['validate_payment']) ? 'checked="checked"' : '' }}>
				  	<label>Keur mollie betalingen</label>
				</div>
			</div>
		</div>

		<div class="two fields">
			<div class="field">
			   	<div class="ui slider checkbox">
					<input 
				  		type="checkbox" 
				  		name="call_list"
				  		{{ isset($cronjobSettings['call_list']) ? 'checked="checked"' : '' }}>
				  	<label>Voeg bedrijven toe aan bellijst <span class="ui red small header color">nieuw</span></label>
				</div>
			</div>
		</div>

		<br /><br />

		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
		<?php echo Form::close() ?>
	</div>

	<div class="ui bottom attached tab segment" data-tab="discount">
		<?php echo Form::open(array('url' => 'admin/settings/discount', 'method' => 'post', 'class' => 'ui form', 'files' => true)) ?>
		<h4>Header</h4>

		<div class="fields">
			<div class="four wide field">
			   	<label>Afbeelding</label>
			    <?php echo Form::file('discount_image4'); ?>
			</div>
		</div>

		<div class="fields">
			<div class="twelve wide field">
			    <label>Breedte</label>
			    <?php echo Form::text('discount_width4', isset($discountSettings['discount_width4']) ? $discountSettings['discount_width4'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Hoogte</label>
			    <?php echo Form::text('discount_height4', isset($discountSettings['discount_height4']) ? $discountSettings['discount_height4'] : ''); ?>
			</div>
		</div>

		<h4>Voordeelpas - Sidebar</h4>

		<div class="fields">
			<div class="four wide field">
			   	<label>Afbeelding</label>
			    <?php echo Form::file('discount_image'); ?>
			</div>

			<div class="twelve wide field">
			    <label>Van (prijs)</label>
			    <?php echo Form::text('discount_old', isset($discountSettings['discount_old']) ? $discountSettings['discount_old'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Voor (prijs)</label>
			    <?php echo Form::text('discount_new', isset($discountSettings['discount_new']) ? $discountSettings['discount_new'] : ''); ?>
			</div>
		</div>

		<div class="fields">
			<div class="twelve wide field">
			    <label>Breedte</label>
			    <?php echo Form::text('discount_width', isset($discountSettings['discount_width']) ? $discountSettings['discount_width'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Hoogte</label>
			    <?php echo Form::text('discount_height', isset($discountSettings['discount_height']) ? $discountSettings['discount_height'] : ''); ?>
			</div>
		</div>

		<div class="field">
			<label>Link naar pagina (URL)</label>
			<?php echo Form::text('discount_url', isset($discountSettings['discount_url']) ? $discountSettings['discount_url'] : ''); ?>
		</div>

		<div class="field">
			<label>Bekeken:</label>
			{{ Setting::get('discount.views') == NULL ? 0 : Setting::get('discount.views') }}x
		</div>

		<h4>Voordeelpas - Pagina</h4>

		<div class="fields">
			<div class="four wide field">
			   	<label>Afbeelding</label>
			    <?php echo Form::file('discount_image3'); ?>
			</div>

			<div class="twelve wide field">
			    <label>Van (prijs)</label>
			    <?php echo Form::text('discount_old3', isset($discountSettings['discount_old3']) ? $discountSettings['discount_old3'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Voor (prijs)</label>
			    <?php echo Form::text('discount_new3', isset($discountSettings['discount_new3']) ? $discountSettings['discount_new3'] : ''); ?>
			</div>
		</div>
		
		<div class="fields">
			<div class="twelve wide field">
			    <label>Breedte</label>
			    <?php echo Form::text('discount_width3', isset($discountSettings['discount_width3']) ? $discountSettings['discount_width3'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Hoogte</label>
			    <?php echo Form::text('discount_height3', isset($discountSettings['discount_height3']) ? $discountSettings['discount_height3'] : ''); ?>
			</div>
		</div>

		<div class="field">
			<label>Link naar pagina (URL)</label>
			<?php echo Form::text('discount_url3', isset($discountSettings['discount_url3']) ? $discountSettings['discount_url3'] : ''); ?>
		</div>

		<div class="field">
			<label>Bekeken:</label>
			{{ Setting::get('discount.views3') == NULL ? 0 : Setting::get('discount.views3') }}x
		</div>

		<div class="ui checkbox">
			<label>Verwijder afbeelding</label>
			<input type="checkbox" name="remove_image3">
		</div>

		<h4>Opwaarderen</h4>
		
		<div class="fields">
			<div class="four wide field">
			   	<label>Afbeelding</label>
			    <?php echo Form::file('discount_image2'); ?>
			</div>
		</div>

		<div class="fields">
			<div class="twelve wide field">
			    <label>Breedte</label>
			    <?php echo Form::text('discount_width2', isset($discountSettings['discount_width2']) ? $discountSettings['discount_width2'] : ''); ?>
			</div>

			<div class="twelve wide field">
			    <label>Hoogte</label>
			    <?php echo Form::text('discount_height2', isset($discountSettings['discount_height2']) ? $discountSettings['discount_height2'] : ''); ?>
			</div>
		</div>

		<div class="field">
			<label>Link naar pagina (URL)</label>
			<?php echo Form::text('discount_url2', isset($discountSettings['discount_url2']) ? $discountSettings['discount_url2'] : ''); ?>
		</div>

		<div class="field">
			<label>Bekeken:</label>
			{{ Setting::get('discount.views2') == NULL ? 0 : Setting::get('discount.views2') }}x
		</div>

		<div class="ui checkbox">
			<label>Verwijder afbeelding</label>
			<input type="checkbox" name="remove_image2">
		</div>

		<br /><br />

		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
		<?php echo Form::close() ?>
	</div>

	<div class="ui bottom attached tab segment" data-tab="eetnu">
		<?php echo Form::open(array('url' => 'admin/settings/eetnu', 'method' => 'post', 'class' => 'ui form', 'files' => true)) ?>
		<h4>Keuken</h4>
		<div class="two fields">
			<div class="field">
			   	<?php 
			   	echo Form::select(
			   		'kitchens[]', 
			   		$kitchens,
					(isset($kitchensSettings['kitchens']) && $kitchensSettings['kitchens'] != 'null' ? json_decode($kitchensSettings['kitchens']) : ''),
					array('multiple' => true, 'class' => 'multipleSelect')
			   	);
			   	?>
			</div>
		</div>

		<h4>Steden</h4>
		Welke steden zijn <strong>NIET</strong> toegestaan? Alle steden die zijn geselecteerd worden niet meegenomen.<br /><br />
		<div class="two fields">
			<div class="field">
			   	<?php 
			   	echo Form::select(
			   		'cities[]', 
			   		$cities,
					(isset($kitchensSettings['cities']) && $kitchensSettings['cities'] != 'null' ? json_decode($kitchensSettings['cities']) : ''),
					array('multiple' => true, 'class' => 'multipleSelect')
			   	);
			   	?>
			</div>
		</div>

		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
		<?php echo Form::close() ?>
	</div>

	<div class="ui bottom attached tab segment" data-tab="invoices">
		Dit gedeelte is bedoeld voor het versturen van de facturen van de opstartkosten. Vul hieronder het bedrag in die de bedrijven moeten betalen voor aanmelding bij UWvoordeelpas. Dit geldt alleen voor <strong>NIEUWE</strong> bedrijven die komen uit de bellijst.<br><br>
		<?php echo Form::open(array('url' => 'admin/settings/invoices', 'method' => 'post', 'class' => 'ui form')) ?>
			<div class="field">
			   	<div class="ui slider checkbox">
				  	<input 
					  	type="checkbox" 
				  		name="services_noshow"
				  		{{ isset($invoicesSettings['services_noshow']) ? 'checked="checked"' : '' }}>
				  	<label>Geen opstartkosten</label>
				</div>
			</div>

			<div class="two fields">
			<div class="field">
				<label>Naam</label>
				<?php echo Form::text('name', isset($invoicesSettings['services_name']) ? $invoicesSettings['services_name'] : ''); ?>
			</div>

			<div class="field">
				<label>Perodiek</label>
				Eenmalig
			</div>
		</div>	

		<div class="two fields">
			<div class="field">
				<label>Prijs</label>
				<?php echo Form::number('price', isset($invoicesSettings['services_price']) ? $invoicesSettings['services_price'] : ''); ?>
			</div>	

			<div class="field">
				<label>BTW</label>
				<?php echo Form::number('tax', isset($invoicesSettings['services_tax']) ? $invoicesSettings['services_tax'] : ''); ?>
			</div>	
		</div>	

		<button class="ui tiny button" type="submit"><i class="plus icon"></i> Opslaan</button>
		<?php echo Form::close() ?>
	</div>
	
	       <div class="ui bottom attached tab segment" data-tab="nieuwsbrief">
		<?php echo Form::open(array('url' => 'admin/settings/newsletter', 'method' => 'post', 'class' => 'ui form')) ?>
		           <div class="field"> 
Stuur nieuwsbrief  <input type="text" name="emailto"><br></div>
<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
<div class="field"> 
				<button class="ui tiny button" type="submit"><i class="plus icon"></i> Stuur nieuwsbrief</button>
		</div>
		<?php echo Form::close() ?>
		</div>
	
</div>
@stop