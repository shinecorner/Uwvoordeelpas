@inject('affiliateHelper', 'App\Helpers\AffiliateHelper')

<?php @$browser = Session::get('browser'); ?>   
<div class="extension-install-overlay" style="display: none;">
    <div class="extension-install-fade">
        <div class="text {{$browser['name']}}">
            <h3>Klik hier!</h3>
            <p style="text-align: left;">Gebruik alle fantastische functionaliteiten van de uwvoordeelpas.nl Spaarhulp!</p>            
        </div>
    </div>
</div>
<?php 
$compatible_browser_array = array('Chrome','Firefox', 'Opera');
?>
<div id="sliderImage" class="slider{{ Request::is('admin/*') == TRUE ? ' admin' : '' }}" >


    @if (Route::getCurrentRoute()->uri() == '/')

    <section id="home" class="scroll-section root-sec grey lighten-5 home-wrap">
        <div class="sec-overlay">
            <div class="container">
                <div class="row">                    
                    <div class="col-sm-12">
                        <div class="home-inner">                            
                            <div class="center-align home-content">								                                                                                         
                                <?php if (($userAuth == FALSE) OR ( $userAuth && $userInfo->extension_downloaded == 0)): ?>
                                    <h1 class="home-title">Activeer de spaarhulp en ontvang direct €5.- </h1>
                                    <h2 class="home-subtitle">Spaar nu automatisch bij wel 2000+ webshops. <br>
                                        Deze betalen u tot wel 10% dinertegoed bij iedere aankoop!</h2>
                                    <?php if(in_array($browser['name'], $compatible_browser_array)):?>
                                        <?php if ($userAuth == FALSE): ?>
                                            <button data-browser="{{$browser['name']}}" class="login button_action" data-type="login" data-redirect="{{ URL::full('/').'?extension_download_btn=1' }}">Ja ik wil ook sparen!</button>
                                        <?php elseif ($userAuth && $userInfo->extension_downloaded == 0): ?>
                                            <button data-browser="{{$browser['name']}}" id="header_extension_button" class="install-button-ext button_action">Ja ik wil ook sparen!</button>
                                        <?php endif; ?>    
                                    <?php else:?>
                                            <button data-browser="{{$browser['name']}}" class="incompatible_browser_ext button_action">Ja ik wil ook sparen!</button>
                                    <?php endif; ?>        
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- .container end -->
            <div class="section-call-to-area">
                <div class="container">
                    <div class="row">
                        <a href="#activation" class="btn-floating btn-large button-middle call-to-about section-call-to-btn animated activation btn-show" data-section="#about">
                            <i class="mdi-navigation-expand-more"></i>
                        </a>
                    </div>
                </div>
                <!-- .container end -->
            </div>
        </div>
    </section>
    @endif

    @if ((Route::getCurrentRoute()->uri() == '/') && (($userAuth == FALSE) OR ( $userAuth && $userInfo->extension_downloaded == 0)))
    <section id="activation">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-6 col2">
                        <img src="{{ asset('images/laptop.png') }}" alt="laptop" class="laptop">
                        <div class="text">
                            <span class="start">Start nu en otvang!</span>
                            <strong class="bax">€
                                <strong class="number">5.-</strong></strong>
                            <p>90 dagen geldig op alle menu’s van </p>
                            <!-- <span class='crop'>UWvoordeelpas!</span> -->
                        </div>
                    </div>
                    <div class="col-sm-6">

                        <h1>Activeer de spaarhulp en ontvang direct €5.- </h1>
                        <h4>Spaar nu automatisch bij wel 2000+ webshops. <br> Deze betalen u tot wel 10% dinertegoed bij iedere aankoop!</h4>
                        <br>
                        <?php if(in_array($browser['name'], $compatible_browser_array)):?>
                            <?php if ($userAuth == FALSE): ?>
                                <button data-browser="{{$browser['name']}}" class="login button_action" data-type="login" data-redirect="{{ URL::full('/').'?extension_download_btn=1' }}">Ja ik wil ook sparen!</button>
                            <?php elseif ($userAuth && $userInfo->extension_downloaded == 0): ?>
                                <button data-browser="{{$browser['name']}}" id="section_extension_button" class="install-button-ext button_action">Ja ik wil ook sparen!</button>
                            <?php endif; ?>
                        <?php else:?>
                            <button data-browser="{{$browser['name']}}" class="incompatible_browser_ext button_action">Ja ik wil ook sparen!</button>
                        <?php endif;?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif



    @if (Route::getCurrentRoute()->uri() == '/' && $userAuth == FALSE)
    <section id="how_it_works">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Hoe werkt het?</h1>
                    <div class="col-sm-3 coll">
                        <a href="{{ url('tegoed-sparen') }}">
                            <img src="{{ url('images/how_it_works_1.png') }}"  alt="{{ isset($contentBlock[49]) ? strip_tags($contentBlock[49]) : '1. Shopt u ook online?' }}">
                            {!! isset($contentBlock[49]) ? $contentBlock[49] : '1. Shopt u ook online?' !!}
                            <!-- <p>uw online aankoop<br>begint hier!</p> -->
                        </a>
                    </div>
                    <div class="col-sm-3 coll">
                        <a href="{{ url('tegoed-sparen') }}">
                            <img src="{{ url('images/how_it_works_2.png') }}" alt="{{ isset($contentBlock[50]) ? strip_tags($contentBlock[50]) : '2. Spaar bij 1500+ Webshops!' }}">
                            {!! isset($contentBlock[50]) ? $contentBlock[50] : '2. Spaar bij 2000+ Webshops!' !!}
                            <!-- <p>U spaart tot vel 10%<br>van uw aankoop</p> -->
                        </a>
                    </div>
                    <div class="col-sm-3 coll">
                        <a href="{{ url('search') }}">
                            <img src="{{ url('images/how_it_works_3.png') }}" alt="{{ isset($contentBlock[51]) ? strip_tags($contentBlock[51]) : '3. Reserveer met uw spaartegoed!' }}">
                            {!! isset($contentBlock[51]) ? $contentBlock[51] : '3. Reserveer met uw spaartegoed!' !!}
                            <!-- <p>reserveer direct<br>bij veel restaurants</p> -->
                        </a>
                    </div>
                    <div class="col-sm-3 coll">
                        <a href="{{ url('tegoed-sparen') }}">
                            <img src="{{ url('images/how_it_works_4.png') }}" alt="{{ isset($contentBlock[52]) ? strip_tags($contentBlock[52]) : '4. Geniet van uw spaartegoed!' }}">
                            {!! isset($contentBlock[52]) ? $contentBlock[52] : '4. Geniet van uw spaartegoed!' !!}
                            <!-- <p>u betaald heel simpel<br>met uw spaargeld</p> -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="clear"></div>
    @endif


    @if((Route::getCurrentRoute()->uri() == '/' && $userAuth == true) OR (Route::getCurrentRoute()->uri() == '/' && $userAuth && $userInfo->extension_downloaded == 0))
    <!--
<div class="homepage_block_container">
    <div class="homepage_block_1">
            <a href="">
                    <img style=" width: auto; height: 315px;" src="{{ url('images/landingpage_notebook.jpeg') }}">
            </a>
    </div>
    if($userAuth && $userInfo->extension_downloaded == 0)
    <div class="homepage_block_2">

            <h3 style="color: #808080; margin: 48px 29px 0px 0px; font-size: 1.9em; text-align: center;"><em>"Wilt u na 1 klik automatisch tot<br> wel 10% sparen bij 2000+ webshops?"</em></h3>
             
            if(strtolower($browser['name']) == 'chrome')
            <script type="text/javascript">
                    function chromeInstallFunction() {
                            chrome.webstore.install('https://chrome.google.com/webstore/detail/kfnndmokhnlhhblfedaeebnonfjbihpo', function () {
                    alert('success');
                            }, function(error, errorCode) {
                    alert(errorCode + "-----------" + error);
                            })
                            return false;
                    };
            </script>
            endif 
            if(strtolower($browser['name']) == 'firefox')               
            <a  style="margin-top: 80px; display: inline-block;" class="homepage_btn install {{$browser['name']}}" 
                    href="/firefox.xpi" iconURL="/images/icons/android-icon-48x48.png">Ja! Ik wil gratis sparen!</a>
            endif
            if(strtolower($browser['name']) == 'chrome')
            <a href="#" onclick="chromeInstallFunction();" id="install-button" style="margin-top: 80px; display: inline-block;" class="homepage_btn install {{$browser['name']}}">Ja! Ik wil gratis sparen!</a>
    endif

    </div> 
    -->
    @endif
    <span style="clear: both;"></span>
</div>




@push('inner_scripts')
<script type="text/javascript">
    var is_download_ext = "<?php echo (app('request')->has('extension_download_btn') && (app('request')->get('extension_download_btn') == '1')) ? '1' : '0'; ?>";
    $(function () {        
        $('.install-button-ext').click(function (e) {
            var browser = $(this).attr('data-browser');
            if (browser == 'Firefox') {
                $(".extension-install-overlay").show().delay(6000).fadeOut("slow");
                window.location = baseUrl + 'firefox.xpi';
            }
            else if ((browser == 'Chrome') || (browser == 'Opera')) {
                $(".extension-install-overlay").show().delay(6000).fadeOut("slow");
                chrome.webstore.install('https://chrome.google.com/webstore/detail/kfnndmokhnlhhblfedaeebnonfjbihpo', function () {
//                    alert('success');
                }, function (error, errorCode) {
//                    alert(errorCode + "-----------" + error);
                });
            }
            else {               
                sweetAlert(" ", "Sorry momenteel ondersteunen we alleen de browsers: Chrome, Firefox en Opera.");
            }
            e.preventDefault();
        });
        if ((is_download_ext == '1') && ($('#section_extension_button').length > 0)) {
            $('#section_extension_button').trigger("click");
        }
        $('.incompatible_browser_ext').click(function(){
            sweetAlert(" ", "Sorry momenteel ondersteunen we alleen de browsers: Chrome, Firefox en Opera.");
        });
    });
</script>
@endpush