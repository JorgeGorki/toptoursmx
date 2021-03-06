<?php
if(isset($item_id) and $item_id):
    $item = STCart::find_item($item_id);

    $tour = $item_id;
    
    $check_in = $item['data']['check_in'];
    $check_out = $item['data']['check_out'];
    $type_tour=$item['data']['type_tour'];
    $duration = isset($item['data']['duration']) ? $item['data']['duration'] : 0;
    $tour_price_by = get_post_meta($tour, 'tour_price_by', true);

    $date_diff = STDate::dateDiff($check_in,$check_out);

    $adult_number = intval($item['data']['adult_number']);
    $adult_label = get_post_meta($tour, 'adult_label', true);
    $child_number = intval($item['data']['child_number']);
    $child_label = get_post_meta($tour, 'child_label', true);
    $infant_number = intval($item['data']['infant_number']);
    $infant_label = get_post_meta($tour, 'infant_label', true);
    $extras = isset($item['data']['extras']) ? $item['data']['extras'] : array();

    $hotel_package = isset($item['data']['package_hotel']) ? $item['data']['package_hotel'] : array();
    $activity_package = isset($item['data']['package_activity']) ? $item['data']['package_activity'] : array();
    $car_package = isset($item['data']['package_car']) ? $item['data']['package_car'] : array();
    $flight_package = isset($item['data']['package_flight']) ? $item['data']['package_flight'] : array();
    $discount_rate = isset($item['data']['discount_rate']) ? $item['data']['discount_rate'] : '';
    $discount_type = get_post_meta($item_id, 'discount_type', true);
?>
<div class="service-section">
    <div class="service-left">
        <h4 class="title"><a href="<?php echo get_permalink($tour)?>"><?php echo get_the_title($tour)?></a></h4>
        <?php
        $address = get_post_meta( $item_id, 'address', true);
        if( $address ):
            ?>
            <p class="address"><?php echo TravelHelper::getNewIcon('Ico_maps', '#666666', '15px', '15px', true); ?><?php echo esc_html($address); ?> </p>
            <?php
        endif;
        ?>
    </div>
    <div class="service-right">
        <?php echo get_the_post_thumbnail($tour,array(110,110,'bfi_thumb'=>true), array('alt' => TravelHelper::get_alt_image(get_post_thumbnail_id($tour )), 'class' => 'img-responsive'));?>
    </div>
</div>
<div class="info-section">
    <ul>
        <?php if($tour_price_by != 'fixed_depart'){ ?>
            <li>
                <span class="label">
                    <?php echo __('Tour type', ST_TEXTDOMAIN); ?>
                </span>
                <span class="value">
                     <?php
                     if($type_tour == 'daily_tour'){
                         echo __('Daily Tour', ST_TEXTDOMAIN);
                     }elseif($type_tour == 'specific_date'){
                         echo __('Special Date', ST_TEXTDOMAIN);
                     }
                     ?>
                </span>
            </li>
            <?php if($type_tour == 'daily_tour'): ?>
            <li>
                <span class="label">
                    <?php echo __('Departure date', ST_TEXTDOMAIN); ?>
                </span>
                <span class="value">
                    <?php echo date_i18n( TravelHelper::getDateFormat(), strtotime( $check_in ) ); ?>
                    <?php
                        $start = date( TravelHelper::getDateFormat(), strtotime( $check_in ) );
                        $end   = date( TravelHelper::getDateFormat(), strtotime( $check_out ) );
                        $date  = date( 'd/m/Y h:i a', strtotime( $check_in ) ) . '-' . date( 'd/m/Y h:i a', strtotime( $check_out ) );
                        $args  = [
                            'start' => $start,
                            'end'   => $end,
                            'date'  => $date
                        ];
                    ?>
                    <a class="st-link" style="font-size: 12px;" href="<?php echo add_query_arg( $args, get_the_permalink( $item_id ) ); ?>"><?php echo __( 'Edit', ST_TEXTDOMAIN ); ?></a>
                </span>
            </li>
            <?php else: ?>
                <li>
                <span class="label">
                    <?php echo __('Date', ST_TEXTDOMAIN); ?>
                </span>
                    <span class="value">
                    <?php echo date_i18n( TravelHelper::getDateFormat(), strtotime( $check_in ) ); ?>
                        -
                        <?php echo date_i18n( TravelHelper::getDateFormat(), strtotime( $check_out ) ); ?>
                        <?php
                        $start = date( TravelHelper::getDateFormat(), strtotime( $check_in ) );
                        $end   = date( TravelHelper::getDateFormat(), strtotime( $check_out ) );
                        $date  = date( 'd/m/Y h:i a', strtotime( $check_in ) ) . '-' . date( 'd/m/Y h:i a', strtotime( $check_out ) );
                        $args  = [
                            'start' => $start,
                            'end'   => $end,
                            'date'  => $date
                        ];
                        ?>
                        <a class="st-link" style="font-size: 12px;" href="<?php echo add_query_arg( $args, get_the_permalink( $item_id ) ); ?>"><?php echo __( 'Edit', ST_TEXTDOMAIN ); ?></a>
                    </span>
                </li>
            <?php endif; ?>
            <?php
            /*Starttime*/
            if(isset($item['data']['starttime']) && !empty($item['data']['starttime'])){
                ?>
                <li>
                <span class="label">
                    <?php echo __('Start time', ST_TEXTDOMAIN); ?>
                </span>
                    <span class="value">
                        <?php echo esc_html($item['data']['starttime']); ?>
                    </span>
                </li>
                <?php
            }
            ?>
            <?php if($type_tour == 'daily_tour' and $duration): ?>
                <li>
                    <span class="label">
                        <?php echo __('Duration', ST_TEXTDOMAIN); ?>
                    </span>
                        <span class="value">
                         <?php
                         echo STTour::get_duration_unit($item_id);
                         ?>
                    </span>
                </li>
            <?php endif; ?>
        <?php }else{ ?>
            <li><b><?php echo __('Fixed Departure', ST_TEXTDOMAIN); ?></b></li>
            <li>
                <span class="label">
                    <?php echo __('Start', ST_TEXTDOMAIN); ?>
                </span>
                <span class="value">
                     <?php
                     echo date_i18n(TravelHelper::getDateFormat(), strtotime($check_in));
                     ?>
                </span>
            </li>
            <li>
                <span class="label">
                    <?php echo __('End', ST_TEXTDOMAIN); ?>
                </span>
                <span class="value">
                     <?php
                     echo date_i18n(TravelHelper::getDateFormat(), strtotime($check_out));
                     ?>
                </span>
            </li>
        <?php } ?>


        <!--Add Info-->
        <li class="ad-info">
            <ul>
                <?php if($adult_number) {?>
                <li><span class="label"><?php
                
                    if(! empty($adult_label)){
                        echo __($adult_label, ST_TEXTDOMAIN); 
                    }else{
                        echo __('Number of Adult', ST_TEXTDOMAIN); 
                    }
                     ?></span><span class="value"><?php echo esc_attr($adult_number); ?></span></li>
                <?php } ?>
                <?php if($child_number) {?>
                    <li><span class="label"><?php
                    if(! empty($child_label)){
                        echo __($child_label, ST_TEXTDOMAIN); 
                    }else{
                        echo __('Number of Children', ST_TEXTDOMAIN); 
                    }    ?></span><span class="value"><?php echo esc_attr($child_number); ?></span></li>
                <?php } ?>
                <?php if($infant_number) {?>
                    <li><span class="label"><?php 
                        if(! empty($infant_label)){
                            echo __($infant_label, ST_TEXTDOMAIN); 
                        }else{
                            echo __('Number of Infant', ST_TEXTDOMAIN); 
                        }?></span><span class="value"><?php echo esc_attr($infant_number); ?></span></li>
                <?php } ?>
            </ul>
        </li>


        <?php if(isset($extras['value']) && is_array($extras['value']) && count($extras['value'])): ?>
            <li>
                <span class="label"><?php echo __('Extra', ST_TEXTDOMAIN); ?></span>
            </li>
            <li class="extra-value">
                    <?php
                    foreach ($extras['value'] as $name => $number):
                        $number_item = intval($extras['value'][$name]);
                        if ($number_item <= 0) $number_item = 0;
                        if ($number_item > 0):
                            $price_item = floatval($extras['price'][$name]);
                            if ($price_item <= 0) $price_item = 0;
                            ?>
                            <span class="pull-right">
                            <?php echo esc_html($extras['title'][$name]) . ' (' . TravelHelper::format_money($price_item) . ') x ' . esc_attr($number_item) . ' ' . __('Item(s)', ST_TEXTDOMAIN); ?>
                            </span> <br/>
                        <?php endif;
                    endforeach;
                    ?>
            </li>
        <?php endif; ?>
        <?php
        if(isset($item['data']['deposit_money'])):
            $deposit      = $item['data']['deposit_money'];
            if(!empty($deposit['type']) and !empty($deposit['amount'])){
                $deposite_amount = '';
                $deposite_type = '';
                switch($deposit['type']){
                    case "percent":
                        $deposite_amount = $deposit['amount'] . ' %';
                        $deposite_type = __('percent', ST_TEXTDOMAIN);
                        break;
                    case "amount":
                        $deposite_amount = TravelHelper::format_money($deposit['amount']);
                        $deposite_type = __('amount', ST_TEXTDOMAIN);
                        break;
                } ?>
                <li>
                    <span class="label">
                        <?php echo esc_html(__('Deposit',ST_TEXTDOMAIN)) ?>
                        <?php echo ' '. esc_html($deposite_type) ?>
                    </span>
                    <span class="value pull-right">
                        <?php
                        echo esc_html($deposite_amount);
                        ?>
                    </span>
                </li>
            <?php }
        endif; ?>

        <!-- Tour Package -->
        <?php if(is_array($hotel_package) && count($hotel_package)): ?>
            <li>
                <p class="booking-item-payment-price-title"><?php _e("Selected Hotel Package",ST_TEXTDOMAIN) ?></p>
                <p class="booking-item-payment-price-amount">
                    <?php
                    foreach($hotel_package as $k_hp => $v_hp): ?>
                        <span class="pull-right">
                                    <?php echo esc_html($v_hp->hotel_name) . ' ('.TravelHelper::format_money($v_hp->hotel_price).')'; ?>
                                </span> <br />
                    <?php endforeach;?>
                </p>
            </li>
        <?php  endif; ?>
        <?php if(is_array($activity_package) && count($activity_package)): ?>
            <li>
                <p class="booking-item-payment-price-title"><?php _e("Selected Activity Package",ST_TEXTDOMAIN) ?></p>
                <p class="booking-item-payment-price-amount">
                    <?php
                    foreach($activity_package as $k_hp => $v_hp): ?>
                        <span class="pull-right">
                                    <?php echo esc_html($v_hp->activity_name ). ' ('.TravelHelper::format_money($v_hp->activity_price).')'; ?>
                                </span> <br />
                    <?php endforeach;?>
                </p>
            </li>
        <?php  endif; ?>
        <?php if(is_array($car_package) && count($car_package)): ?>
            <li>
                <p class="booking-item-payment-price-title"><?php _e("Selected Car Package",ST_TEXTDOMAIN) ?></p>
                <p class="booking-item-payment-price-amount">
                    <?php
                    foreach($car_package as $k_hp => $v_hp): ?>
                        <span class="pull-right">
                                    <?php echo esc_html($v_hp->car_name ). ' ('.TravelHelper::format_money($v_hp->car_price).') x ' . esc_html($v_hp->car_quantity); ?>
                                </span> <br />
                    <?php endforeach;?>
                </p>
            </li>
        <?php  endif; ?>
        <?php if(is_array($flight_package) && count($flight_package)): ?>
            <li>
                <p class="booking-item-payment-price-title"><?php _e("Selected Flight Package",ST_TEXTDOMAIN) ?></p>
                <p class="booking-item-payment-price-amount">
                    <?php
                    foreach($flight_package as $k_fp => $v_fp): ?>
                        <span class="pull-right">
                                    <?php
                                    $name_flight_package = $v_fp->flight_origin . ' <i class="fa fa-long-arrow-right"></i> ' . esc_html($v_fp->flight_destination);
                                    $price_flight_package = '';
                                    if($v_fp->flight_price_type == 'business'){
                                        $price_flight_package = TravelHelper::format_money($v_fp->flight_price_business);
                                    }else{
                                        $price_flight_package = TravelHelper::format_money($v_fp->flight_price_economy);
                                    }
                                    ?>
                                    <?php echo esc_html( $name_flight_package) . ' (' . esc_html($price_flight_package) . ')'; ?>
                                </span> <br />
                    <?php endforeach;?>
                </p>
            </li>
        <?php  endif; ?>
        <!-- End Tour Package -->
    </ul>
</div>
<div class="coupon-section">
    <h5><?php echo __('Coupon Code', ST_TEXTDOMAIN); ?></h5>

    <form method="post" action="<?php the_permalink() ?>">
        <?php if (isset(STCart::$coupon_error['status'])): ?>
            <div
                class="alert alert-<?php echo STCart::$coupon_error['status'] ? 'success' : 'danger'; ?>">
                <p>
                    <?php echo STCart::$coupon_error['message'] ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <?php $code = STInput::post('coupon_code') ? STInput::post('coupon_code') : STCart::get_coupon_code();?>
            <input id="field-coupon_code" value="<?php echo esc_attr($code ); ?>" type="text" name="coupon_code" />
            <input type="hidden" name="st_action" value="apply_coupon">
            <?php if(st()->get_option('use_woocommerce_for_booking','off') == 'off' && st()->get_option('booking_modal','off') == 'on' ){ ?>
                <input type="hidden" name="action" value="ajax_apply_coupon">
                <button type="submit" class="btn btn-primary add-coupon-ajax"><?php echo __('APPLY', ST_TEXTDOMAIN); ?></button>
                <div class="alert alert-danger hidden"></div>
            <?php }else{ ?>
                <button type="submit" class="btn btn-primary"><?php echo __('APPLY', ST_TEXTDOMAIN); ?></button>
            <?php } ?>
        </div>
    </form>
</div>
<div class="total-section">
    <?php
    $price_type = STTour::get_price_type($item_id);
    if($price_type == 'person' or $price_type == 'fixed_depart'){
        $data_price = STPrice::getPriceByPeopleTour($item_id, strtotime($check_in), strtotime($check_out),  $adult_number, $child_number, $infant_number);
    }else{
        $data_price = STPrice::getPriceByFixedTour($item_id, strtotime($check_in), strtotime($check_out));
    }
    $origin_price = floatval($data_price['total_price']);
    $sale_price = STPrice::getSaleTourSalePrice($item_id, $origin_price, $type_tour, strtotime($check_in));
    $extra_price = isset($item['data']['extra_price']) ? floatval($item['data']['extra_price']) : 0;

    $hotel_package_price = isset($item['data']['package_hotel_price']) ? floatval($item['data']['package_hotel_price']) : 0;
    $activity_package_price = isset($item['data']['package_activity_price']) ? floatval($item['data']['package_activity_price']) : 0;
    $car_package_price = isset($item['data']['package_car_price']) ? floatval($item['data']['package_car_price']) : 0;
    $flight_package_price = isset($item['data']['package_flight_price']) ? floatval($item['data']['package_flight_price']) : 0;

    $price_coupon = floatval(STCart::get_coupon_amount());
    $price_with_tax = STPrice::getPriceWithTax($sale_price + $extra_price + $hotel_package_price + $activity_package_price + $car_package_price + $flight_package_price);
    $price_with_tax -= $price_coupon;
    ?>
    <ul>
        <?php if($price_type == 'person' or $price_type == 'fixed_depart'){ ?>
            <li>
                <span class="label">
                    <?php
                    if(! empty($adult_label)){
                        echo __($adult_label . ' Subtotal', ST_TEXTDOMAIN); 
                    }else{
                        echo __('Adult Subtotal', ST_TEXTDOMAIN); 
                    } ?>
                </span>
                <span class="value">
                    <?php if($data_price['adult_price']) echo TravelHelper::format_money($data_price['adult_price']); else echo '0'; ?>
                </span>
            </li>

            <li>
                <span class="label">
                    <?php if(! empty($child_label)){
                        echo __($child_label . ' Subtotal', ST_TEXTDOMAIN); 
                    }else{
                        echo __('Children Subtotal', ST_TEXTDOMAIN); 
                    } ?>
                </span>
                <span class="value">
                    <?php if($data_price['child_price']) echo TravelHelper::format_money($data_price['child_price']); else echo '0'; ?>
                </span>
            </li>

            <li>
                <span class="label">
                    <?php if(! empty($infant_label)){
                        echo __($infant_label . ' Subtotal', ST_TEXTDOMAIN); 
                    }else{
                        echo __('Infant Subtotal', ST_TEXTDOMAIN); 
                    } ?>
                </span>
                <span class="value">
                    <?php if($data_price['infant_price']) echo TravelHelper::format_money($data_price['infant_price']); else echo '0'; ?>
                </span>
            </li>
        <?php }else{ ?>
            <li>
                <span class="label">
                    <?php echo __('Price', ST_TEXTDOMAIN); ?>
                </span>
                <span class="value">
                    <?php if($data_price['total_price']) echo TravelHelper::format_money($data_price['total_price']); else echo '0'; ?>
                </span>
            </li>
        <?php } ?>

        <?php
        if ( !empty($discount_rate) && isset($discount_type) ) : ?>
            <li>
                <span class="label"><?php echo __('Discount', ST_TEXTDOMAIN); ?></span>
                <span class="value">
                    <?php
                    if($discount_type == 'amount'){
                        echo TravelHelper::format_money($discount_rate);
                    }else{
                        echo esc_html($discount_rate).'%';
                    } ?>
                </span>
            </li>
            <?php
        endif;
        ?>

        <li><span class="label"><?php echo __('Subtotal', ST_TEXTDOMAIN); ?></span><span class="value"><?php echo TravelHelper::format_money($sale_price); ?></span></li>
        <?php if(isset($extras['value']) && is_array($extras['value']) && count($extras['value']) && isset($item['data']['extra_price'])): ?>
            <li>
                <span class="label"><?php echo __('Extra Price', ST_TEXTDOMAIN); ?></span>
                <span class="value"><?php echo TravelHelper::format_money($extra_price); ?></span>
            </li>
        <?php endif; ?>

    <!--Package Amount-->
    <?php if(is_array($hotel_package) && count($hotel_package)): ?>
        <li>
                <span class="label"><?php echo __('Hotel Package', ST_TEXTDOMAIN); ?></span>
            <span class="value"><?php echo TravelHelper::format_money($hotel_package_price); ?></span>
            </li>
        <?php endif; ?>
        <?php if(is_array($activity_package) && count($activity_package)): ?>
            <li>
                <span class="label"><?php echo __('Activity Package', ST_TEXTDOMAIN); ?></span>
                <span class="value"><?php echo TravelHelper::format_money($activity_package_price); ?></span>
            </li>
        <?php endif; ?>
        <?php if(is_array($car_package) && count($car_package)): ?>
            <li>
                <span class="label"><?php echo __('Car Package', ST_TEXTDOMAIN); ?></span>
                <span class="value"><?php echo TravelHelper::format_money($car_package_price); ?></span>
            </li>
        <?php endif; ?>
        <?php if(is_array($flight_package) && count($flight_package)): ?>
            <li>
                <span class="label"><?php echo __('Flight Package', ST_TEXTDOMAIN); ?></span>
                <span class="value"><?php echo TravelHelper::format_money($flight_package_price); ?></span>
            </li>
        <?php endif; ?>
        <!--End Package amount-->

        <li><span class="label"><?php echo __('Tax', ST_TEXTDOMAIN); ?></span><span class="value"><?php echo STPrice::getTax().' %'; ?></span></li>
        <?php if (STCart::use_coupon()):
            if($price_coupon < 0) $price_coupon = 0;
            ?>
            <li>
                <span class="label text-left">
                    <?php printf(st_get_language('coupon_key'), STCart::get_coupon_code()) ?> <br/>
                    <?php if(st()->get_option('use_woocommerce_for_booking','off') == 'off' && st()->get_option('booking_modal','off') == 'on' ){ ?>
                        <a href="javascript: void(0);" title="" class="ajax-remove-coupon" data-coupon="<?php echo STCart::get_coupon_code(); ?>"><small class='text-color'>(<?php st_the_language('Remove coupon') ?> )</a>
                    <?php }else{ ?>
                        <a href="<?php echo st_get_link_with_search(get_permalink(), array('remove_coupon'), array('remove_coupon' => STCart::get_coupon_code())) ?>"
                           class="danger"><small class='text-color'>(<?php st_the_language('Remove coupon') ?> )</small></a>
                    <?php } ?>
                </span>
                <span class="value">
                        - <?php echo TravelHelper::format_money( $price_coupon ) ?>
                </span>
            </li>
        <?php endif; ?>

        <?php
        if(isset($item['data']['deposit_money']) && count($item['data']['deposit_money']) && floatval($item['data']['deposit_money']['amount']) > 0):

            $deposit      = $item['data']['deposit_money'];

            $deposit_price = $price_with_tax;

            if($deposit['type'] == 'percent'){
                $de_price = floatval($deposit['amount']);
                $deposit_price = $deposit_price * ($de_price /100);
            }elseif($deposit['type'] == 'amount'){
                $de_price = floatval($deposit['amount']);
                $deposit_price = $de_price;
            }
            ?>
            <li>
                <span class="label"><?php echo __('Total', ST_TEXTDOMAIN); ?></span>
                <span class="value"><?php echo TravelHelper::format_money($price_with_tax); ?></span>
            </li>
            <li>
                <span class="label"><?php echo __('Deposit', ST_TEXTDOMAIN); ?></span>
                <span class="value">
                    <?php echo TravelHelper::format_money($deposit_price); ?>
                </span>
            </li>
            <?php
            $total_price = 0;
            if(isset($item['data']['deposit_money']) && floatval($item['data']['deposit_money']['amount']) > 0){
                $total_price = $deposit_price;
            }else{
                $total_price = $price_with_tax;
            }
            ?>
            <?php if(!empty($item['data']['booking_fee_price'])){
            $total_price = $total_price + $item['data']['booking_fee_price'];
            ?>
            <li>
                <span class="label"><?php echo __('Fee', ST_TEXTDOMAIN); ?></span>
                <span class="value"><?php echo TravelHelper::format_money($item['data']['booking_fee_price']); ?></span>
            </li>
            <?php } ?>
            <li class="payment-amount">
                <span class="label"><?php echo __('Pay Amount', ST_TEXTDOMAIN); ?></span>
                <span class="value">
                        <?php echo TravelHelper::format_money($total_price); ?>
                </span>
            </li>

        <?php else: ?>
            <?php if(!empty($item['data']['booking_fee_price'])){
                $price_with_tax = $price_with_tax + $item['data']['booking_fee_price'];
                ?>
                <li>
                    <span class="label"><?php echo __('Fee', ST_TEXTDOMAIN); ?></span>
                    <span class="value"><?php echo TravelHelper::format_money($item['data']['booking_fee_price']); ?></span>
                </li>
            <?php } ?>
            <li class="payment-amount">
                <span class="label"><?php echo __('Pay Amount', ST_TEXTDOMAIN); ?></span>
                <span class="value"><?php echo TravelHelper::format_money($price_with_tax); ?></span>
            </li>
        <?php endif; ?>
    </ul>
</div>
<?php
endif;
?>
