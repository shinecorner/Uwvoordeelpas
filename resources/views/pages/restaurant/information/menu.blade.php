<h5 class="ui header thin">
    <i class="icon food"></i>
    <div class="content">Menu</div>
</h5>
  @if(isset($deals) && count($deals))
        <?php foreach ($deals as $deal): ?>
<div class="row">
<!--    <div class="col-md-3">
        <img id="image" src="{{ url($media[0]->getUrl('175Thumb')) }}" class="img-responsive" alt="" />
    </div>-->

    <div class="col-md-6">
      
            <div class="text">
                <h2>{{ $deal->name }}</h2>
                <p><?php echo html_entity_decode($deal->description); ?></p>
                <p>{{-- str_limit($deal->description, (isset($limitChar) ? $limitChar : 210)) --}}</p>
            </div>
        </div>

        <div class="col-md-3 pull-right">
            <div class="mdg_price">
                <p>
                    <span style="position: relative; font-weight: normal;  ">
                        <s>&euro; {{ $deal->price_from }}</s>
                    </span>
                    <span style="position: relative;  font-weight: bold; margin-top:10px; margin-left: 10px;">
                        &euro; {{ $deal->price }}
                    </span>
                </p>
               
                <?php /*
                if (isset($deal->reservation_count) && ($deal->reservation_count[0]['total_reservation'] >= $deal->total_amount)) {
                    ?>
                    <a class="deal_btn" style="float: right;" href="javascript:void(0)">SOLD OUT</a>
                    <?php
                } else {
                    ?>
                    <button class="btn-success pull-right" style="font-size: 16px;">
                        <span><img id="image" src="https://www.live.uwvoordeelpas.nl/media/1723/cart.png" class="img-responsive" alt="" /></span>
                        Reserveer nu
                        <span><i class="right chevron icon divider"></i></span>
                    </button>
                    <?php
                } */
                ?>
            </div>
        </div>
    </div>
    <div class="clear"></div>
<?php endforeach; ?>
@endif