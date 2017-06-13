<a href="#" data-activates="slide-out" class="button-collapse2"><i class="material-icons material-icons2">menu</i></a>

<ul id="slide-out" class="side-nav2 right-aligned" style="overflow:auto" >
		<li><a href="{{ url('news') }}"><i class="material-icons">assignment</i> Nieuws</a></li>
		<li><a href="{{ url('tegoed-sparen') }}"><i class="material-icons">monetization_on</i> Tegoed sparen</a></li>
		<li><a href="{{ url('voordeelpas/buy') }}"><i class="material-icons">credit_card</i> Voordeelpas</a></li>
		 @if($userCompany OR $userWaiter)
		 <li><a href="{{ url('faq/3/restaurateurs') }}"><i class="material-icons">help</i> Veelgestelde vragen</a></li>
		@else
		 <li><a href="{{ url('faq/2/restaurateurs') }}"><i class="material-icons">help</i> Veelgestelde vragen</a></li>
		@endif
	
		<li><a href="#" class="item search-full-open"><i class="material-icons">search</i> Zoeken</a></li>
		@if($userAuth)
		
			@if( $userCompany != 1 && $userWaiter != 1 )
			<li class="fixed-row">
			   <a class="active">
				  {{ ($userInfo->name != '' ? strtoupper($userInfo->name) : 'Gebruiker') }}
				</a>
			</li>
			<li>
				<a href="{{ url('account/reservations/saldo') }}" class="item">
					<i class="material-icons">euro_symbol</i><strong>Spaartegoed:</strong> {{$userInfo->saldo }}
				</a>
			</li>
			<li><a href="{{ url('payment/charge') }}" ><i class="material-icons">euro_symbol</i> Saldo opwaarderen</a></li>
			<li><a href="{{ url('account') }}" ><i class="material-icons">euro_symbol</i> Mijn gegevens</a></li>
			<li><a href="{{ url('account/reviews') }}" ><i class="material-icons">thumb_up</i> Mijn recensies</a></li>
			<li><a href="{{ url('account/reservations') }}" ><i class="material-icons">local_dining</i> Mijn reserveringen</a></li>
                        <li><a href="{{ url('account/future-deals') }}" ><i class="material-icons">reorder</i> Mijn vouchers</a></li>
			<li><a href="{{ url('account/barcodes') }}" ><i class="material-icons">reorder</i> Mijn voordeelpas</a></li>
			<li><a href="{{ url('account/favorite/companies') }}" ><i class="material-icons">favorite_border</i> Mijn favoriete restaurants</a></li>
			<li><a href="{{ url('logout') }}" ><i class="material-icons">touch_app</i> Uitloggen</a></li>
			@endif
			
			@inject('companyReservation', 'App\Models\CompanyReservation')           
			@include('template/navigation/company')
			@include('template/navigation/callcenter')
			@include('template/navigation/admin')
			<li class="divider"> </li>
		@else 										
			<li><a id="registerButton2" class="register button item" href="#" ><i class="material-icons">vpn_key</i> Aanmelden</a></li>
			<li><a class="login button" data-type="login" href="#"><i class="material-icons">exit_to_app</i> Inloggen</a></li>
			<li><a href="{{ url('hoe-werkt-het') }}"><i class="material-icons">description</i> Hoe werkt het?</a></li>
			<li><a href="{{ url('algemene-voorwaarden') }}"><i class="material-icons">book</i> Voorwaarden</a></li>
		@endif
		
		
</ul>

