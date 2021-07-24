<?php
   /*
   Plugin Name: Custom Margin
   description: 
   Version: 1.2
   Author URI: https://tenantimprovement.com/
   */

add_action( 'admin_menu', 'my_custom_menu_page_new' );
function my_custom_menu_page_new() {
    add_menu_page(
        __( 'Custom Margin', 'textdomain' ),
        'Custom Margin',
        'manage_options',
        'custom_margin',
        'call_custom_margin',
        '',
        6
    );
}


function call_custom_margin()
{

  $get_rules = get_option("save_custom_margin_rules");
  if(!empty($get_rules))
  {
    $margintype = $get_rules['margintype'];
    $newmargin = $get_rules['newmargin'];
    
    
  }

  if(isset($_POST['savenewmargin']))
{
    update_option('save_custom_margin_rules',$_POST);
    ?>
    <script>
   jQuery( document ).ready(function() {
    alert('Your margin detials successfully saved')
    location.reload();
        });
   </script>
<?php
}
   ?>
   <style>
   .newmargindivhide{display: none;}
 </style>
<form name="custom_margin_form" class="custom_margin_form" method="post">
   <div><input type="radio" name="margintype" class="margintype" value="standard" <?php echo $check = $margintype=='standard'? 'checked':''  ?>>Standard</div>
   <div><input type="radio" name="margintype" class="margintype" value="new" <?php echo $check = $margintype=='new'? 'checked':''  ?>>New</div>
   <div class="newmargindiv <?php echo $checkdiv = $margintype=='standard'? 'newmargindivhide':''  ?>">
    <input type="text" name="newmargin" id="newmargin" class="newmargin" value="<?php echo $newmargin; ?>">
      
   </div>
   <div><input type="submit" value="save" name="savenewmargin" id="savenewmargin" class="savanewmargin"></div>
</form>
<script>
   jQuery( document ).ready(function() {
      
    jQuery('input:radio[name="margintype"]').change(
    function(){
        if (jQuery(this).is(':checked') && jQuery(this).val() == 'new') {
            jQuery('.newmargindiv').show()
            var selectmargin = 'new';
            
        }
        else
        {
            jQuery('.newmargindiv').hide()
            var selectmargin = 'standard';
        }
    });

});
</script>
<?php
}



 
function bbloomer_alter_price_display( $price_html, $product ) {
    
    $get_rules = get_option("save_custom_margin_rules");
    $margintype = $get_rules['margintype'];
    $newmargin = $get_rules['newmargin'];
    
    if($margintype=='new' && !empty($newmargin))
    {
        $orig_price = wc_get_price_to_display( $product );
        $price_html = wc_price( $orig_price/(1-($newmargin/100)));
    }
    return $price_html;
 
}

$getpst = get_option("save_price_filter_rules_plugin_active",true); 
if($getpst!=1)
{
    
add_filter("woocommerce_after_shop_loop_item_title","wc_add_short_description_product_list");
add_action( 'woocommerce_before_calculate_totals', 'wc_add_short_description_borad_product_card', 1002, 1 );
add_action( 'woocommerce_short_description','wc_add_short_description_borad_list_detials',999,1);
add_filter( 'woocommerce_cart_item_name', 'cfwc_cart_item_name2', 11, 3 );
//add_filter( 'woocommerce_get_price_html', 'bbloomer_alter_price_display', 9999, 2 );
}
function wc_add_short_description_product_list()
{
     global $product,$wpdb;
    $get_rules = get_option("save_custom_margin_rules");
    $margintype = $get_rules['margintype'];
    $newmargin = $get_rules['newmargin'];
    
    if($margintype=='new' && !empty($newmargin))
    {
        // $orig_price = wc_get_price_to_display( $product );
        // echo $price_html = wc_price( $orig_price/(1-($newmargin/100)));

        $sale_price = $product->get_sale_price();
        $cartoon_qty = get_post_meta($product->get_id(),'carton_quantity', true);
        $measurement_label = get_post_meta($product->get_id(),'measurement_label', true);
        $news = $sale_price/(1-($newmargin/100))/$cartoon_qty;
     $news2 = number_format((float)$news, 2, '.', '').'/'.$measurement_label;
?>
<div itemprop="short-description" class="asfadadf2 zcxfd52">
   <?php
   
 echo $price_box .= '<div style="width: 100%;" class="half-box onlistiapf"><h4>$'.$news2.'</h4></div>';

    ?>
    <style>
        .half-box h4 {
            color: #9a0000;
            font-size: 15px;
            line-height: 29px;
        }
    </style>
</div>
<style>
    div.asfadadf.zcxfd5,div[itemprop="description"]
    {
        display:none;
    }
    div[itemprop="short-description"]
    {
        color: black;
        font-weight: normal;
    }
</style>
<?php
    }
}

function wc_add_short_description_borad_list_detials($excerpt){
    global $product;
    if(is_product()){
        $price_box = '';
     
    $get_rules = get_option("save_custom_margin_rules");
    $margintype = $get_rules['margintype'];
    $newmargin = $get_rules['newmargin'];
    
    if($margintype=='new' && !empty($newmargin))
    {
    
    $sale_price = $product->get_sale_price();
                $cartoon_qty = get_post_meta($product->get_id(),'carton_quantity', true);
        $sale_price_type = get_post_meta($product->get_id(),'measurement_label', true);
        $sale_price = $sale_price/(1-($newmargin/100))/$cartoon_qty;
                
    $price_box .= '<div class="price_box"><div style="width: 100%;" class="half-box teadfss"><h5>Our Sale Price</h5><h4>'.wc_price($sale_price).'/'.$sale_price_type.'</h4><s style="display: none;">'.wc_price($regular_price).'</s></div><div style="display: none;" class="half-box second"><h5>Your Savings</h5>
<div class="lcr"><div>MSRP</div>
<div><s class="old-price">'.wc_price($regular_price).'</s></div>
</div>
<div class="lcr"><div>Save '.round($dis_perc, 2).'%</div>
<div>'.wc_price($diff).'</div>
</div>
</div></div>';
?>
<style>
    .price_box {
        display: flex;
        flex-wrap: wrap;
        border: 1px solid #ccc;
    }
    .price_box .half-box {
        width: 50%;
        padding: 15px;
        text-align: center;
    }
    .half-box.second {
        background: #eee;
        border-left: 1px solid #ccc;
    }
    .half-box h5 {
        font-size: 13px;
        color: #5c5c5c;
        font-weight: normal;
    }
    .half-box h4 {
        color: #9a0000;
        font-size: 27px;
        line-height: 29px;
    }
    .price_box .lcr {
        display: flex;
        justify-content: space-between;
        font-weight: normal;
        font-size: 13px;
    }
    .half-box.second h5 {
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .woocommerce-product-details__short-description .price_box:last-child {
        display: none;
    }
</style>
<?php
            
        }
        
        else {
        }
        $excerpt = $price_box.$excerpt;
    }
    return $excerpt;
}

function wc_add_short_description_borad_product_card($cart_object){
    
    foreach ( WC()->cart->get_cart() as $key => $value ) {
        $product_id = $value['product_id'];  
        $product = wc_get_product( $product_id );
        $get_rules = get_option("save_custom_margin_rules");        
        $margintype = $get_rules['margintype'];
    $newmargin = $get_rules['newmargin'];
    
        if($margintype=='new' && !empty($newmargin))
        {
         
            $sale_price = $product->get_sale_price();
            $cartoon_qty = get_post_meta($product->get_id(),'carton_quantity', true);
            $sale_price_type = get_post_meta($product->get_id(),'measurement_label', true);
            $carton_price = $sale_price/(1-($newmargin/100));
         
        }   
       $value['data']->set_price( $carton_price );
  }
     
}


function cfwc_cart_item_name2( $name, $cart_item, $cart_item_key ) 
{
    
    $get_rules = get_option("save_custom_margin_rules");
    $margintype = $get_rules['margintype'];
    $newmargin = $get_rules['newmargin'];
    
    if($margintype=='new' && !empty($newmargin))
    {
       $product = wc_get_product( $cart_item['product_id'] );
     $sale_price = $product->get_sale_price();
     $carton_qty = $product->get_meta( 'carton_quantity' );
      $sale_price_type = get_post_meta($product->get_id(),'measurement_label', true);
      $sale_price = $sale_price/(1-($newmargin/100))/$carton_qty;
      $sale_price = number_format((float)$sale_price, 2, '.', '');
      $measurement_price_type = get_post_meta($product->get_id(),'measurement_label', true);

        $measurement_label = strtolower(str_replace(" ", "", $product->get_meta( 'measurement_label' )));
        $name .= sprintf(
            '<p class="taw-print zcxvzxc3">
            <span>Covers %s %s @ $%s per %s</span>
            </p>',
            esc_html( number_format( $carton_qty * $cart_item["quantity"],2) ),
            esc_html( $measurement_label ),
            esc_html($sale_price),
            esc_html( $measurement_price_type )
        );
        echo '<style>p.taw-print.zcxvzxc{display: none;}</style>';
    }
    
    return $name;
}
?>