<header id="navigation" class="root-sec white nav header">
			 <div class="container">
				<div class="row">
					<div class="col-sm-12">
					
						<div class="nav-inner">
							<nav class="primary-nav">
								<div class="clearfix nav-wrapper">
								 
								 	@include('template.sidemenu')
									
									<!-- <a href="{{ url('/')}}" class="left brand-logo menu-smooth-scroll" data-section="#home">
									   <img src="{{asset('images/logo.png')}}" alt="">
									</a>
									
									 <div class="mobile-profile pp-container">
										<a href="{{ url('/')}}">
											<img src="{{ asset('images/logo.png') }}" alt="">
										 </a>
									 </div> -->
									    <a href="#" data-activates="mobile-top" class="button-collapse"> <i class="material-icons material-icons2">menu</i></a> 										
										<div class="brand-logo">
											 <a href="{{ url('/')}}" >
												<img src="{{ asset('images/logo.png') }}" alt="" class="responsive-img">
											 </a>																	
										 </div>
									 
										<ul class="right side-nav" id="mobile-top"> <!-- center-menu- inline-menu -->
											<form action="<?php echo url('search'); ?>" method="GET" class="form">
											<li class="search-form-li sk">										  
												<div class="input-field">
												   <div id="usersCompaniesSearch2" class="search form focus">
													<label class="label-icon" for="search"><i class="mdi-actio2n-search sss"></i></label>											
													<input id="search" name="q" type="search" value="{{ Request::segment(1) == 'search' ? Request::get('q') : '' }}" placeholder="{{ trans('app.keyword') }}" class="prompt" autocomplete="off" >
												  </div>
												</div>
											</li>
											
											<li>
												<label for="datepicker">
													<img src="{{asset('images/m2.png') }}" alt="m2">
													<input id="datepicker" placeholder="Datum" name="date" class="datepicker1 quantity" data-filter-todate="yes" data-time="#sltime" type="text" {{ (Request::has('date')) ?  'value='.Request::has('date') : '' }}>
												</label>
											</li>
											<li>
												<img src="images/m3.png" alt="m3">
												<select id="sltime" name="sltime" class="quantity">
													@php
														// Check time
														if (Request::segment(1) == 'search' && Request::has('sltime')) 
															$current_time=date('H:i', strtotime(Request::get('sltime')));
														else	
															$current_time = (isset($disabled[0])) ? $disabled[0] : '';
														
														$datetime = new DateTime();												
													@endphp
												   
												   
												   @foreach ($getTimes as $time)
														@php  
															$timed = date_create_from_format('H:i',$time);															
														@endphp
														@if ($time >= '00:00' && $time >= '08:00' && $timed->getTimestamp() >= $datetime->getTimestamp())
															<option value="{{ $time }}" data-value="{{ $time }}" data-dd="0" >{{ $time }}</option>
														@else 
															<option value="{{ $time }}" data-value="{{ $time }}" data-dd="0" style="display:none">{{ $time }}</option>
														@endif													
													@endforeach
												</select>
											</li>
											<li>
												<img src="images/m4.png" alt="m4">
												@php  
												   $current_p = ((Request::get('persons') != '') ? Request::get('persons') : (($userAuth && $userInfo->kids != 'null' && $userInfo->kids != NULL && $userInfo->kids != '[""]') ? $userInfo->kids : 2))
												@endphp
											
												<select name="persons" class="quantity quantity-expand">
													<!-- <option value="0" disabled="disabled">Pers</option> -->
													  @for ($i = 1; $i <= 10; $i++)
														<option  value="{{ $i }}" data-value="{{ $i }}" {{ ($i == $current_p ) ? "selected" : "" }}>{{ $i }} {{ $i == 1 ? 'persoon' : 'personen' }}</option>
													  @endfor
												</select>												
											</li>
											<li class="mobile-center">
											    <button class="zoek" id ="searchDesktop" type="submit">zoek</button>
											</li>
								
										</form>
										</ul>
									</div>
							   </nav>
						</div>
						<!-- menu end -->
						
						</div>
					</div>
				</div> 
				<!-- .container end -->				

</header>
	
	    