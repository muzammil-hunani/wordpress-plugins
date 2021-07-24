<?php
/**
Plugin Name: Price Filter For Mesurment
description: Price filter will apply on selected child stores, this filter price will overwrite existing price as per condition.
version:1.0.0
author: Riyaz Lohiya
**/


function get_product_category($setruonarra)
{
 
    $get_rules = get_option("save_price_filter_rules");
    $oldocunt = count($get_rules);
    $args = array(
        'number'     => $number,
        'orderby'    => $orderby,
        'order'      => $order,
        'hide_empty' => $hide_empty,
        'include'    => $ids,
        'parent' => 0,
    );
    $product_categories = get_terms( 'product_cat', $args );
    echo "<div id='list1' class='dropdown-check-list' tabindex='100'>";
    echo "<ul class='items'>";
echo "<select name='prod_term[$oldocunt][]'  class='test_multiche'  multiple='multiple'>";
    // echo "<li class='allmaincategoryli'><input name='prod_term[$oldocunt][]' class='allmaincategorys' id='prod_term$oldocunt' type='checkbox' value='all'>All</li>";
    foreach ($product_categories as $prod_term) 
    {
        $args2 = array(
            'hierarchical' => 1,
            'show_option_none' => '',
            'hide_empty' => 0,
            'parent' => $prod_term->term_id,
            'taxonomy' => 'product_cat'
        );
        $subcategories = get_categories($args2);
        $checked = in_array($prod_term->term_id,$setruonarra) ? "checked" : "";
        // echo "<li class='maincategory'><input class='prod_term$oldocunt' name='prod_term[$oldocunt][]' $checked id='prod_term' type='checkbox' value='$prod_term->term_id'>$prod_term->name</li>";                        
        echo "<li class='maincategory'><option class='maincategory prod_term$oldocunt' name='prod_term[$oldocunt][]' $checked id='prod_term' type='checkbox' value='$prod_term->term_id'>$prod_term->name</option></li>";                        
        if(!empty($subcategories))
        {
            foreach ($subcategories as $prod_term2) 
            {
                $checked2 = in_array($prod_term2->term_id,$setruonarra) ? "checked" : "";
// echo "<li class='subcategory'><input class='prod_term$oldocunt' name='prod_term[$oldocunt][]' $checked2 id='prod_term' type='checkbox' value='$prod_term2->term_id'>$prod_term2->name</li>";                        
 echo "<li class='subcategory'><option class='subcategory prod_term$oldocunt' name='prod_term[$oldocunt][]' $checked2 id='prod_term' type='checkbox' value='$prod_term2->term_id'>$prod_term2->name</option></li>";                        
            }
        }
    }
    echo '</select>';
    echo '</ul>';
    echo '</div>';

}
function my_custom_menu_page() {
    add_menu_page(
        __( 'Price Filter - Installed', 'textdomain' ),
        'Price Filter - Installed',
        'manage_options',
        'price_filter',
        'call_price_filter',
        '',
        6
    );
}
add_action("wp_ajax_wm_ajax_svg_upload", "wm_ajax_svg_upload");

function wm_ajax_svg_upload()
  {
      if(isset($_POST['action']))
      {
             $plsytus= $_POST['isacctiv'];
          update_option('save_price_filter_rules_plugin_active',$plsytus);   
      }
    // echo json_encode(array('status'=>true,'url'=>$upload['url']));
    exit;    
  }

add_action( 'admin_menu', 'my_custom_menu_page' );
function call_price_filter(){
    
    $args = array(
        'number'     => $number,
        'orderby'    => $orderby,
        'order'      => $order,
        'hide_empty' => $hide_empty,
        'include'    => $ids,
        'parent' => 0,
    );
    $product_categories = get_terms( 'product_cat', $args );
    
    $get_rules = get_option("save_price_filter_rules");
    $setruon = $get_rules['prod_term'];
    $setruonarra = explode(',',$setruon);
    if(isset($_POST['save_price_rule'])){
        
        
        
        $get_rules = array();
        
        if(isset($_POST['is_enable']) && is_array($_POST['is_enable'])){
            foreach($_POST['is_enable'] as $i => $single){
                $get_rules[$i]['is_enable'] = $single;
                $get_rules[$i]['mesurment_price'] = $_POST['mesuarment_price'][$i];
                $get_rules[$i]['me_price_min'] = $_POST['me_price_min'][$i];
                $get_rules[$i]['me_price_max'] = $_POST['me_price_max'][$i];
                $get_rules[$i]['me_price_type'] = $_POST['me_price_type'][$i];
                $get_rules[$i]['prod_term'] = implode(',',$_POST['prod_term'][$i]);
            }
        }
        update_option('save_price_filter_rules',$get_rules);
    }
?>
<div style='padding: 10px 0;    margin: 10px 0;'>

<?php 
$getpst = get_option("save_price_filter_rules_plugin_active",true); 
if($getpst==1)
{
    $checkedus = 'checked';
}
?>
<input type='checkbox' name='pluginactive' <?php echo $checkedus; ?> value='1' class='pluginactive'>Plugin Active

<script>
    jQuery( document ).ready(function() {
        jQuery( window ).resize()
        jQuery(".allmaincategorys").change(function() {
if(this.checked) 
{
var id = jQuery(this).attr('id');
jQuery('.'+id).not(this).prop('checked', this.checked);
}
else
{
var id = jQuery(this).attr('id');
jQuery('.'+id).not(this).prop('checked', this.checked);
}

});
    jQuery(".pluginactive").change(function() {
    if(this.checked) 
    {
    var isacctiv = 1;
    }
    else
    {
    var isacctiv = 0;
    }
     var formdata = new FormData(); 
     formdata.append("action", 'wm_ajax_svg_upload');
     formdata.append("isacctiv", isacctiv);

    jQuery.ajax({
              type: 'POST',
              url: '<?=admin_url("admin-ajax.php")?>',
              traditional: true,
              processData: false,
            contentType: false,     
              data:formdata,
              success: function (response) 
              {
                  if(isacctiv==1)
                  {
                  alert("Plugin Successfully active");
                  }
                  else
                  {
                  alert("Plugin Successfully inactive");    
                  }
                  
              },
              error: function (jqXHR,textStatus,errorThrown) {
                alert("somthing want to wrong");
                
              }
      });
      
    });
    });
    </script>
    
</div>
<h2>
    Price Filter
</h2>
<style>
div#example_filter,div#example_length {
    display: none;
}
    .dropdown-check-list {
        display: inline-block;
    }
    .dropdown-check-list .anchor {
        position: relative;
        cursor: pointer;
        display: inline-block;
        padding: 5px 78px 5px 10px;
        border: 1px solid #ccc;
    }
    
    .dropdown-check-list .anchor:active:after {
        right: 8px;
        top: 21%;
    }
    .subcategory{
        margin-left: 15px;
    }
    /*.dropdown-check-list ul.items {*/
    /*    padding: 2px;*/
    /*    overflow: scroll;*/
    /*    height: 150px;*/
    /*    margin: 0;*/
    /*    border: 1px solid #ccc;*/
    /*    border-top: none;*/
    /*}*/
    .dropdown-check-list ul.items li {
        list-style: none;
    }
    .dropdown-check-list.visible .anchor {
        color: #0094ff;
    }
    .dropdown-check-list.visible .items {
        display: block;
    }
    
</style>
<script>
    jQuery( document ).ready(function() {
        jQuery( window ).resize()
        var checkList = document.getElementById('list1');
        
    });
</script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" rel="stylesheet"/>


<?php
    $option_list = '<select name="filter_cat" id="filter_cat"><option value=""> --- Select --- </option>';
    $re_cat = isset($_REQUEST['filter_cat']) ? $_REQUEST['filter_cat'] : '';
    foreach($product_categories as $prod_term) {
        $selected = ($re_cat==$prod_term->term_id) ? "selected" : "";
        $option_list .="<option value='$prod_term->term_id' ".$selected.">$prod_term->name</option>";
        $args2 = array(
                    'hierarchical' => 1,
                    'show_option_none' => '',
                    'hide_empty' => 0,
                    'parent' => $prod_term->term_id,
                    'taxonomy' => 'product_cat'
                );
                $subcategories = get_categories($args2);
                if(!empty($subcategories))
                {
                    foreach ($subcategories as $prod_term2) 
                    {
                        $selected2 = ($re_cat==$prod_term2->term_id) ? "selected" : "";
                        $option_list .= "<option value='$prod_term2->term_id' ".$selected2.">&nbsp;&nbsp;&nbsp;&nbsp;$prod_term2->name</option>";
                        
                    }
                }
    }
    $option_list .='</select>';
     $reset_btn = (isset($_REQUEST['filter_cat'])) ? '<button style="padding: 5px 10px;margin-left: 10px;  vertical-align: bottom;" type="button" onclick="jQuery(\'#filter_cat\').val(\'\'); jQuery(\'#filter_cat\').change();jQuery(\'.cat_form\').submit();">Reset filter</button>' : '';
    
    echo "<form class='cat_form'><div style='padding: 10px 0px;'><b>Category Filter:</b> ".$option_list."<input type='submit' name='filter_cat_submit' style='padding: 4px 10px;margin-left: 10px;  vertical-align: bottom;'>".$reset_btn."<input type='hidden' name='page' value='price_filter'></div></form>";
    
    
    echo "<form method='post'><button class='button  add_new_rule' type='button'>Add New Rule + </button>
<table border='1' class='price_rules' id='example' class='display' style='width:100%'>
<thead>
<tr class='headtr'>
<td class='tdone'>Enable \ Disable</td>
<td class='tdtwo'>Price Range Label</td>
<td class='tdthree'>Unit of Measure Lable</td>
<td class='tdfour'>Price Range (Low Price / High Price)</td>
<td class='tdfive'>Select category</td>
<td class='tdsix'>Selected Categories</td>

<td></td>
</tr>
</thead>
<tbody>";
    if(empty($get_rules)){
        echo "<tr><td style='text-align:center;'><label><input type='checkbox' class='is_enable'><input type='hidden' name='is_enable[]' value='0'></label></td><td><input type='number' step='0.01' name='mesuarment_price[]'></td><td><input type='text' name='me_price_type[]'></td><td><input type='number' step='0.01' name='me_price_min[]' placeholder='Min'><input type='number' name='me_price_max[]' step='0.01' placeholder='Max'></td><td>";
        get_product_category(array());
        echo "</td><td><span class='dashicons dashicons-trash delete_row' style='color: brown;cursor:pointer;' title='Delete'></span></td></tr>";
    } else {
        foreach($get_rules as $key =>  $single){
            if(is_numeric($key)){
                $enable = $single['is_enable'];
                $checked = ($enable==1) ? "checked" : "";
                $me_price = $single['mesurment_price'];
                $min_price = $single['me_price_min'];
                $max_price = $single['me_price_max'];
                $setruon = $single['prod_term'];
                $me_type = $single['me_price_type'];
                $setruonarra2 = explode(',',$setruon);
                
                $pass = (in_array($_REQUEST['filter_cat'], $setruonarra2)) ? 1 : 0;
                if(!isset($_REQUEST['filter_cat']) || empty($_REQUEST['filter_cat'])){
                    $pass = 1;
                }
                if($pass == 1):
                echo "<tr><td class='tdone1' style='text-align:center;'><label><input type='checkbox' class='is_enable' $checked><input type='hidden' name='is_enable[]' value='$enable'></label></td><td class='tdtwo2'><input type='number' step='0.01' name='mesuarment_price[]' value='$me_price'><span  style='display: none;'>$me_price</span></td><td class='tdthree3'><input type='text' name='me_price_type[]' value='$me_type'><span style='display:none;'>$me_type</span></td><td class='tdfour4'><label></label><input type='number' step='0.01' name='me_price_min[]' placeholder='Min' value='$min_price'><span style='display:none'>$min_price</span><input type='number' step='0.01' name='me_price_max[]' placeholder='Max' value='$max_price'><span style='display:none'>$max_price</span></td>";
                echo "<td class='tdfive5'>";
                echo '<div id="list1" class="dropdown-check-list" tabindex="100">';
                 
             echo '<ul class="items">';
                 $allchecked = in_array('all',$setruonarra2) ? "selected" : "";
                echo "<select name='prod_term[$key][]'  class='test_multiche'  multiple='multiple'>";
                // echo "<option name='prod_term[$key][]' $allchecked class='allmaincategorys' id='prod_term$key' type='checkbox' value='all'>All</option>";
                
                // $allchecked = in_array('all',$setruonarra2) ? "checked" : "";
                // echo "<li class='allmaincategoryli'><input name='prod_term[$key][]' $allchecked class='allmaincategorys' id='prod_term$key' type='checkbox' value='all'>All</li>";
                
                 foreach ($product_categories as $prod_term) 
                 {
                    $args2 = array(
                        'hierarchical' => 1,
                        'show_option_none' => '',
                        'hide_empty' => 0,
                        'parent' => $prod_term->term_id,
                        'taxonomy' => 'product_cat'
                    );
                    $subcategories = get_categories($args2);
                    $checked = in_array($prod_term->term_id,$setruonarra2) ? "selected" : "";
    echo "<li class='maincategory'><option class='maincategory prod_term$key' name='prod_term[$key][]' $checked type='checkbox' value='$prod_term->term_id'>$prod_term->name</option></li>";                        
             //       echo "<option class='maincategory prod_term$key' name='prod_term[$key][]' $checked type='checkbox' value='$prod_term->term_id'>$prod_term->name</option>";                        
                    if(!empty($subcategories))
                    {
                        foreach ($subcategories as $prod_term2) 
                        {
                            $checked2 = in_array($prod_term2->term_id,$setruonarra2) ? "selected" : "";
        echo "<li class='subcategory'><option class='subcategory prod_term$key' name='prod_term[$key][]' $checked2  type='checkbox' value='$prod_term2->term_id'>$prod_term2->name</option></li>";                        
    //  echo "<option class='prod_term$key' name='subcategory prod_term[$key][]' $checked2  type='checkbox' value='$prod_term2->term_id'>$prod_term2->name</option>";                        
                        }
                    }
                 }
                 echo '</select>';
                 echo '</ul>';
                echo '</div>';
                echo "</td>";
                echo "<td class='tdsix6'>";
                echo "<select class='nnewarratd'>";
                foreach ($setruonarra2 as $setruonarraname) 
                {
                    $terms = get_term_by( 'id', $setruonarraname, 'product_cat', 'ARRAY_A' );
                    echo "<option>".$terms['name']."</option>";
                }
                echo "</select>";
                echo "</td>";
                echo "<td class='tdeith8'><span class='dashicons dashicons-trash delete_row' style='color: brown;cursor:pointer;' title='Delete'></span></td></tr>";
                endif;
            }
        }
    }
    echo "</tbody>
</table>";
?>
<style>

td.tdtwo2 {
    width: 8%;
}
td.tdtwo2 input[type="number"] {
    width: 100%;
}
td.tdfour4 {
    width: 23%;
}
td.tdfour4 input[type="number"] {
    width: 44%;
}
td.tdfive5 {
    width: 22%;
}
.dropdown-check-list, .btn-group button.multiselect.dropdown-toggle.btn.btn-default, .wp-core-ui select {
    width: 100%;
}
td.tdsix6 {
    width: 15%;
}
    select#wpseo-filter, select#wpseo-readability-filter {
        display: none;
    }
    
    .btn-group button.multiselect.dropdown-toggle.btn.btn-default {
    height: 32px;
    background: #fff;
    border: 1px solid #7e8993;
    border-radius: 3px;
    padding-left: 10px;
    padding-right: 40px;
    padding-bottom: 3px;
    position: relative;
}

.btn-group button.multiselect.dropdown-toggle.btn.btn-default:before {
    content: "";
    position: absolute;
    background: url(https://catalopiso.com/wp-content/uploads/2020/12/angle-arrow-down.png);
    width: 9px;
    height: 9px;
    background-size: contain;
    background-repeat: no-repeat;
    left: 88%;
    top: 11px;
    opacity: 0.8;
}

.btn-group button.multiselect.dropdown-toggle.btn.btn-default:hover {
    color: #1e8cbe;
}

.btn-group ul.multiselect-container.dropdown-menu {
    background: #fff;
    padding: 10px 0 !important;
    height: 450px;
    width: 22%;
    overflow-y: scroll;
    border: 1px solid #7e8993;
    position: absolute;
    right: 30%;
    margin-left: 0 !important;
    z-index: 999;
}
td input[type="number"] {
    width: 35%;
}

li.prod_term1 {
    margin-left: 10px;
}
li.maincategory.prod_term1 {
    margin-left: 0;
}

.btn-group ul.multiselect-container.dropdown-menu li label {
    padding-left: 15px;
    font-size: 14px;
    color: #32373c;
    display: flex;
    align-items: center;
}

.btn-group ul.multiselect-container.dropdown-menu li label input[type="checkbox"] {
    min-height: 16px;
    min-width: 16px;
}

.btn-group {
    position: relative;
}
.tablenav.top .alignleft.actions {
    display: flex;
}
    
        .btn-group ul.multiselect-container.dropdown-menu {
    background: #fff !important;
    padding: 10px 0 !important;
    height: 450px !important;
    width: 100% !important;
    overflow-y: scroll !important;
    border: 1px solid #7e8993 !important;
    position: absolute !important;
    right: 0 !important;
    margin-left: 0 !important;
}

</style>
 <script>
  var $ =jQuery;
  function sortSelectOptions(selector, skip_first) {
        var options = (skip_first) ? $(selector + ' option:not(:first)') : $(selector + ' option');
        var arr = options.map(function(_, o) { return { t: $(o).text(), v: o.value, s: $(o).prop('selected') }; }).get();
        arr.sort(function(o1, o2) {
          var t1 = o1.t.toLowerCase(), t2 = o2.t.toLowerCase();
          return t1 > t2 ? 1 : t1 < t2 ? -1 : 0;
        }); 
        options.each(function(i, o) {
            o.value = arr[i].v;
            $(o).text(arr[i].t);
            if (arr[i].s) {
                $(o).attr('selected', 'selected').prop('selected', true);
            } else {
                $(o).removeAttr('selected');
                $(o).prop('selected', false);
            }
        }); 
  }
    jQuery(document).ready(function($) {
        jQuery( window ).resize()
        setTimeout(function(){ 
            jQuery('span.multiselect-selected-text').text('Select category')
        jQuery('ul.multiselect-container.dropdown-menu').hide();
            }, 100);
        
        jQuery(document).on("click","button.multiselect.dropdown-toggle.btn.btn-default",function() {
        jQuery(this).closest('td').find('ul.multiselect-container.dropdown-menu').toggle();
        });
        
         sortSelectOptions('select[name="filter_by_site"]', true);
         sortSelectOptions('select[name="prod_term"]', true);
           jQuery('.test_multiche').multiselect();
    });
   
</script>
<?php
echo "<input type='submit' name='save_price_rule' class='button button-primary' style='margin-top:10px;'>
</form>"; ?>
<script>
jQuery(document).ready(function() {
    

jQuery(document).on('click','body *',function(){
    var x=-1;
jQuery( "select.test_multiche" ).each(function() {
    x=x+1; 
jQuery(this).attr( 'name','prod_term['+x+'][]')
console.log(x)
});    
});
     jQuery('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf'
        ]
    } );
    setTimeout(function(){ 
jQuery("table.dataTable thead td:nth-child(1), table.dataTable thead td:nth-child(3), table.dataTable thead td:nth-child(4),table.dataTable thead td:nth-child(6),table.dataTable thead td:nth-child(7)").removeAttr('class');
 }, 1000);

} );
    jQuery(document).on('click', '.delete_row', function(){
        if(confirm('Are you Sure want to delete?')){
            jQuery(this).closest('tr').remove();
        }
    }
                       );
    jQuery(document).on('click','.is_enable', function(){
        if(jQuery(this).prop('checked')){
            jQuery(this).next('input').val(1);
        }
        else {
            jQuery(this).next('input').val(0);
        }
    }
                       );
    jQuery(document).on('click','.add_new_rule', function(){
        
        var rowCount = jQuery('table.price_rules tr').length;
        
       var newhtml = "<?php get_product_category(array()); ?>";
        jQuery('.price_rules tbody').append("<tr><td style='text-align:center;'><label><input type='checkbox' class='is_enable'><input type='hidden' name='is_enable[]' value='0'></label></td><td><input type='number' step='0.01' style='width: 100%;' name='mesuarment_price[]'></td><td><input type='text' name='me_price_type[]' ></td><td><input type='number' name='me_price_min[]' style='width: 44%;' step='0.01' placeholder='Min'><input type='number' step='0.01' style='width: 44%;' name='me_price_max[]' placeholder='Max'></td><td>"+newhtml+"</td><td></td><td><span class='dashicons dashicons-trash delete_row' style='color: brown;cursor:pointer;' title='Delete'></span></td></tr>").show('slow');
        jQuery('.test_multiche').multiselect();
        
        jQuery('ul.multiselect-container.dropdown-menu').hide();
    })
</script>
<?php
}
add_filter("woocommerce_short_description","show_custom_pricing_box_custom",99,1);
function show_custom_pricing_box_custom($excerpt){
    global $product;
    if(is_product()){
        $price_box = '';
        $regular_price = $product->get_regular_price();
        $terms2 = get_the_terms ($product->get_id(), 'product_cat');
        $listofcat = array();
        if(!empty($terms2))
        {
            foreach ($terms2 as $term ) 
            {
                $cat_id = $term->term_id;
                array_push($listofcat,$cat_id);
            }        
        }
        $taw_ver = get_post_meta($product->get_id(),'taw_version', true);
        $get_rules = get_option("save_price_filter_rules");
        
        $apply_price = 0; $apply_price_type = '';
        $mesurment_price = get_post_meta($product->get_id(),"measurement_price",true);
        $cartoon_price = get_post_meta($product->get_id(),'carton_price',true);
        $cartoon_qty = get_post_meta($product->get_id(),'carton_quantity', true);
        
        if(empty($cartoon_qty)){
            $cartoon_qty = 1;
        }
        
        $regular_price = floatval($regular_price/$cartoon_qty);
        if(!empty($mesurment_price)){
            $sale_price = $mesurment_price;
        }
        else {
            if(!empty($sale_price)){
                $sale_price = floatval($sale_price/$cartoon_qty);
            } else {
                $sale_price = floatval($cartoon_price/$cartoon_qty);
            }
        }
        $yesarray = array();
        foreach($get_rules as $key => $single_p){
            if(is_numeric($key))
            {
                $mesurment_prod_term = $single_p['prod_term']; 
                $setruonarra = explode(',',$mesurment_prod_term);
             $getpst = get_option("save_price_filter_rules_plugin_active",true); 
    if($getpst==1){
                
                if($single_p['is_enable'] == 1){
                    
                    if($sale_price >= $single_p['me_price_min'] &&  $sale_price <= $single_p['me_price_max']){
                        
                    $terms2 = get_the_terms ($product->get_id(), 'product_cat');
                    
                    foreach($terms2 as $currntapgeid)
                    {
                        if (in_array($currntapgeid->term_id,$setruonarra))
                        {
                            
                            array_push($yesarray,'yes');
                            $apply_price = $single_p['mesurment_price'];
                            $apply_price_type = $single_p['me_price_type'];
                        }
                    }
                  }
                }
            }
                
            }
        }
        
        if (in_array('yes',$yesarray))
        {
            
            if( $product->is_on_sale()) {
                $sale_price = $product->get_sale_price();
                if($apply_price != 0){
                    $sale_price = $apply_price;
                    $sale_price_type = $apply_price_type;
       
                }
                $diff =  floatval($regular_price) - floatval($sale_price);
                $dis_perc = 100 -(($sale_price*100)/$regular_price);
                if(is_plugin_active('custom_margin/index.php')) 
                {    
                $get_rules2 = get_option("save_custom_margin_rules");
                $margintype = $get_rules2['margintype'];
                $newmargin = $get_rules2['newmargin'];
                if($margintype=='new' && !empty($newmargin))
                {
                $sale_price =  $sale_price/(1-($newmargin/100));
                $sale_price = number_format((float)$sale_price, 2, '.', '');
                }
            }
    $price_box .= '<div class="price_box"><div style="width: 100%;" class="half-box teadfss"><h5>Our Sale Price</h5><h4>'.wc_price($sale_price).$sale_price_type.'</h4><s style="display: none;">'.wc_price($regular_price).'</s></div><div style="display: none;" class="half-box second"><h5>Your Savings</h5>
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
        }
        
        else {
        }
        $excerpt = $price_box.$excerpt;
    }
    return $excerpt;
}
function calculate_price_as_per_rules( $cart_object ) {
    
    
    $get_rules = get_option("save_price_filter_rules");
    foreach ( WC()->cart->get_cart() as $key => $value ) {
        $product_id = $value['product_id'];  
        $product = wc_get_product( $product_id );
        $carton_qty = $product->get_meta( 'carton_quantity' );
        $measurement_price = $product->get_meta( 'measurement_price' );
        $pr = $product->get_price();
        $carton_price = $pr;
        if(!empty($carton_qty)){
            $pr = ($pr/$carton_qty);
            $terms2 = get_the_terms($product_id, 'product_cat');
            $listofcat = array();
            if(!empty($terms2))
            {
                foreach ($terms2 as $term ) 
                {
                    $cat_id = $term->term_id;
                    array_push($listofcat,$cat_id);
                }        
            }
            $setruon = $get_rules['prod_term'];
            foreach($get_rules as $key => $single_p){
                // if(is_numeric($key)){
                //     $setruonarra = explode(',',$setruon);
                //     foreach($setruonarra as $tes)
                //     {
                //         if (in_array($tes,$listofcat))
                //         {
                //             $yesisincateg = 'yes';
                //         }
                //     }
                //     if($yesisincateg=='yes')
                //     {
                //         $getpst = get_option("save_price_filter_rules_plugin_active",true); 
                // if($getpst==1){
                //         if($single_p['is_enable'] == 1){
                //             if($pr >= $single_p['me_price_min'] && $pr <= $single_p['me_price_max']){
                //                 $measurement_price = $single_p['mesurment_price']*$carton_qty;
                //                 $carton_price = $single_p['mesurment_price']*$carton_qty;
                //             } 
                //         }
                // }
                //     }
                // }

                if(is_numeric($key))
                {
                 $mesurment_prod_term = $single_p['prod_term']; 
                $setruonarra = explode(',',$mesurment_prod_term);
             $getpst = get_option("save_price_filter_rules_plugin_active",true); 
                if($getpst==1){   
                    
                    if($single_p['is_enable'] == 1)
                    {
                        
                    $terms2 = get_the_terms ($product->get_id(), 'product_cat');
                    $yesarray = array();
                    foreach($terms2 as $currntapgeid)
                    {
                        
                        if (in_array($currntapgeid->term_id,$setruonarra))
                        {
                        
                        if($pr >= $single_p['me_price_min'] && $pr <= $single_p['me_price_max'])
                        {
                            
                            $measurement_price = $single_p['mesurment_price']*$carton_qty;
                            $carton_price = $single_p['mesurment_price']*$carton_qty;
                        } 
                            
                        }
                    }
                        
                    }
                    }
            }
        }
        if(!empty($carton_price))
        {
            $value['data']->set_price( $carton_price );
        }
    }
}
}
add_action( 'woocommerce_before_calculate_totals', 'calculate_price_as_per_rules', 1001, 1 );
function hstngr_register_widget_new() {
    register_widget( 'hstngr_widget_new' );
}
add_action( 'widgets_init', 'hstngr_register_widget_new' );
class hstngr_widget_new extends WP_Widget {
    function __construct() {
        parent::__construct('mesurment_price_widget_new', __('New Price filter', 'mesurmnet_widget_new_domain'), array( 'description' => __( 'New Price filter widget', 'mesurmnet_widget_new_domain' ) ) );
    }
    public function widget( $args, $instance ) {
        $get_rules = get_option("save_price_filter_rules");
        

        $sort = array();
foreach($get_rules as $k=>$v) 
{
    $sort['me_price_min'][$k] = $v['me_price_min'];
    $sort['me_price_max'][$k] = $v['me_price_max'];
    $sort['mesurment_price'][$k] = $v['mesurment_price'];
    $sort['prod_term'][$k] = $v['prod_term'];
    $sort['me_price_type'][$k] = $v['me_price_type'];
    
}
array_multisort($sort['me_price_min'], SORT_ASC, $sort['me_price_max'], SORT_ASC,$sort['mesurment_price'], SORT_ASC,$sort['prod_term'], SORT_ASC,$sort['me_price_type'], SORT_ASC,$get_rules);


        $setruon = $get_rules['prod_term'];
        $setruonarra = explode(',',$setruon);
        $categorysfds = get_queried_object();
        $currntapgeid = $categorysfds->term_id;
        $getpst = get_option("save_price_filter_rules_plugin_active",true); 
        if($getpst==1){

         foreach($get_rules as $nkey2=>$nval2)
            {
                if(is_numeric($nkey2)) 
            {
               if($nval2['is_enable'] == 1)
                {   
                    $mesurment_prod_term = $nval2['prod_term']; 
                    $setruonarra = explode(',',$mesurment_prod_term);
                    if (in_array($currntapgeid,$setruonarra))
                    {
                    $yesis = 1; 
                }
                }
            }
            }
            if($yesis==1)
            {
        ?>

        <div class="berocket_single_filter_widget berocket_single_filter_widget_17501" data-id="17501" style="">
    <div id="berocket_aapf_single-40" class="et_pb_widget widget_berocket_aapf_single">
        <div class="berocket_aapf_widget-wrapper brw-brand">
            
            
            <!--<div class=" customafdswduig" style="cursor: pointer;">-->
            <!--    <span class="berocket_aapf_widget_show show_button mobile_hide"><i class="fa fa-angle-left ">-->
            <!--        </i></span>-->
            <!--    <h3 class="widget-title berocket_aapf_widget-title" style="">-->
            <!--        <span>Price Range</span>-->
            <!--    </h3>-->
            <!--</div>-->
            <div class="bapf_head bapf_colaps_togl customafdswduig" style="cursor: pointer;">
            <h3 class="bapf_hascolarr">
                Price Range
                <i class="bapf_colaps_smb fa fa-chevron-down bapf_hide_mobile">
                </i>
            </h3>
        </div>
        <style>
        .bapf_head.bapf_colaps_togl.customafdswduig h3.bapf_hascolarr {
    display: flex;
    justify-content: space-between;
}
        </style>
            <ul class="berocket_aapf_widget2" style="" data-scroll_theme="dark" data-widget_id="berocket_aapf_single-40" data-widget_id_number="berocket_aapf_single-40" data-child_parent="" data-attribute="pa_brand" data-type="checkbox" data-count_show="" data-cat_limit="0">
        <?php
        foreach($get_rules as $nkey=>$nval)
        {
            if(is_numeric($nkey)) 
            {
                $me_price_min = $nval['me_price_min']; 
                $me_price_max = $nval['me_price_max']; 
                $mesurment_price = $nval['mesurment_price']; 
                $mesurment_prod_term = $nval['prod_term']; 
                $mesurment_prod_type = $nval['me_price_type']; 
                $setruonarra = explode(',',$mesurment_prod_term);
                
                if($nval['is_enable'] == 1)
                {
                    if (in_array($currntapgeid,$setruonarra))
                    {
?>

<script>
//     jQuery( document ).ready(function() {
//         jQuery('.oldpricefilter').parents('.berocket_single_filter_widget').remove();
//         jQuery('span.woocommerce-Price-amount.amount').remove()
//         jQuery( "label.berocket_label_widgets" ).each(function( index ) {
//             var newes =  jQuery( this ).text().replace('(', '').replace(')', '');
//             jQuery(this).text(newes)
//         });
//     });
    
 </script>


                <li class="berocket_term_parent_0 berocket_term_depth_0 brw-brand-greenworld ">
                    <span><input class="checkboxnew" name="checkboxnew" type="checkbox" value="<?php echo $mesurment_price ?>" data-min="<?php echo $me_price_min?>" data-max="<?php echo $me_price_max ?>" autocomplete="off" ><label data-for="checkbox_1275pa_brand" class="berocket_label_widgets">$<?php echo $mesurment_price.''.$mesurment_prod_type; ?></label>
                    </span>
                </li>
    

<?php
                    }
                }
            // }
            }
        }

        
?>
        </ul>
        </div>
    </div>
</div> 
<script>
    var top_px = jQuery(window).height()/2 + jQuery(window).scrollTop();
    var loader_div = '<div class="berocket_aapf_widget_loading"><div class="berocket_aapf_widget_loading_container" style="top: '+top_px+'px;"><div class="berocket_aapf_widget_loading_top"></div><div class="berocket_aapf_widget_loading_left"></div><div class="berocket_aapf_widget_loading_image"></div><div class="berocket_aapf_widget_loading_right"></div><div class="berocket_aapf_widget_loading_bottom"></div></div></div>'
    jQuery(window).load(function(){
        slectyourArray = [];    

        //jQuery("ul.berocket_aapf_widget2").hide();
//         jQuery(".berocket_single_filter_widget_17501").click(function(){
//   jQuery("ul.berocket_aapf_widget2").show();
// });
        jQuery('ul.berocket_aapf_widget2').hide();
        
        jQuery('.bapf_loader_page').hide();
        jQuery(document).on("change", ".checkboxnew", function(){
            
            jQuery('div#content-area').append('<div class="bapf_loader_page"><div class="bapf_lcontainer"><span class="bapf_loader"><span class="bapf_lfirst"></span><span class="bapf_lsecond"></span></span></div></div>');
            jQuery('.bapf_loader_page').show();           
                   if(this.checked) 
                    {
                      slectyourArray.push(jQuery(this).val());
                    }
                    else
                    {
                        if ((index = slectyourArray.indexOf(jQuery(this).val())) !== -1)
                            slectyourArray.splice(index, 1);
                    }
                   
                   
        yourArray = [];            
        jQuery("input:checkbox[name=checkboxnew]:checked").each(function(){
        yourArray.push(jQuery(this).data('min'));
        yourArray.push(jQuery(this).data('max'));
        
        
        });
   
    
   if (yourArray.length === 0) 
   {
        var url = window.location.href;
    var a = url.indexOf("?");
    var b =  url.substring(a);
    var c = url.replace(b,"");
    url = c;
    window.location.replace(url)
    }
                

                var minmaxval = Math.max.apply(Math,yourArray); // 3
                 var  minval = Math.min.apply(Math,yourArray); // 1
                    
                if(jQuery(".berocket_aapf_widget_loading").length == 0){
                    jQuery("ul.products").append(loader_div);
                }
                var page_url = the_ajax_script.current_page_url;
                if(window.location.search == ''){
                    page_url = page_url+'/?hid_min='+minval+'&hid_max='+minmaxval;
                }
                else {
                    var queryParams = new URLSearchParams(window.location.search);
                    queryParams.set("hid_min", minval);
                    queryParams.set("hid_max", minmaxval);
                    page_url = page_url+"/?"+queryParams.toString();
                }
                
                history.replaceState(null, null, page_url);
                jQuery("input#text_pa_br_price2_2").change();
                jQuery.get( page_url, function( data ) {
                    var left_html = jQuery(data).find('#left-area').html();
                    if(left_html != ''){
                        jQuery('#left-area').html(left_html);
                    }
                    jQuery(".berocket_aapf_widget_loading").remove();
                    jQuery('.bapf_loader_page').hide();           
                });
            
        });
        
                jQuery(document).on("click", ".customafdswduig", function(){
                    console.log(slectyourArray)
        jQuery("ul.berocket_aapf_widget2").toggle();
            
    });
    jQuery(document).on("click", ".berocket_aapf_widget-title_div", function(){
        console.log(slectyourArray)
        jQuery("ul.berocket_aapf_widget2").hide();
        
            
    });
    
    jQuery(document).on("click", ".berocket_label_widgets", function(){
        
        console.log(slectyourArray)
        jQuery("ul.berocket_aapf_widget2").hide();
        
            jQuery("input:checkbox[name=checkboxnew]").each(function(){
                
        var istest = jQuery.inArray(jQuery(this).val(),slectyourArray);
        
         if(istest!= -1)
         {
              jQuery(this).attr("checked","checked");
         }
         else
         {
              jQuery(this).prop('checked',false)
         }
         
         
        
    });
    
    });
    
    jQuery( document ).ajaxComplete(function() {
        jQuery("ul.berocket_aapf_widget2").hide();
        jQuery("input:checkbox[name=checkboxnew]").each(function(i){
            var istest = jQuery.inArray(jQuery(this).val(),slectyourArray);
        
         if(istest!= -1)
         {
              jQuery(this).prop('checked',true)
         }
         else
         {
              jQuery(this).prop('checked',false)
         }
                
    });
            
    });
    
    });
</script>
<?php
}
        }
    }
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) )
            $title = $instance[ 'title' ];
        else
            $title = __( 'Default Title', 'mesurmnet_widget_domain' );
?>
<p>
    <label for="
<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="
<?php echo $this->get_field_id( 'title' ); ?>" name="
<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="
<?php echo esc_attr( $title ); ?>" />
</p>
<?php
    }
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}
function modify_query_new($query) {
    if( ! is_admin() && $query->is_main_query()) {
        if( isset( $_GET['hid_min'] )) {
            $query->set( 'meta_query', array (
                array(
                    'key'     => 'measurement_price',
                    'value'   => array( $_GET['hid_min'], $_GET['hid_max'] ),
                    'type'    => 'DECIMAL(10,3)',
                    'compare' => 'BETWEEN',
                )
            ));
        }
    }
}
add_action( 'pre_get_posts', 'modify_query_new' );

add_action( 'woocommerce_after_shop_loop_item_title', 'wc_add_short_description_borad2' );

function wc_add_short_description_borad2() {
    global $product,$wpdb;
    
     $hide = get_post_meta($product->get_id(), 'hide_sale_tag', true);
    ?>
        <div itemprop="short-description" class="asfadadf zcxfd5">
            <?php  
            $getpst = get_option("save_price_filter_rules_plugin_active",true); 
            // if($taw_ver == 1) {
            
                
                
        $price_box = '';
        $regular_price = $product->get_regular_price();
        $terms2 = get_the_terms ($product->get_id(), 'product_cat');
        $listofcat = array();
        if(!empty($terms2))
        {
            
            foreach ($terms2 as $term ) 
            {
                
                $cat_id = $term->term_id;
                array_push($listofcat,$cat_id);
            }        
        }
        $taw_ver = get_post_meta($product->get_id(),'taw_version', true);
        $get_rules = get_option("save_price_filter_rules");
        
        $apply_price = 0; $apply_price_type = '';
         $mesurment_price = get_post_meta($product->get_id(),"measurement_price",true);
         $cartoon_price = get_post_meta($product->get_id(),'carton_price',true);
         $cartoon_qty = get_post_meta($product->get_id(),'carton_quantity', true);
        
        if(empty($cartoon_qty)){
            $cartoon_qty = 1;
        }
        
        @$regular_price = floatval($regular_price/$cartoon_qty);
        
        if(!empty($mesurment_price)){
            $sale_price = $mesurment_price;
        }
        else {
            if(!empty($sale_price)){
                $sale_price = floatval($sale_price/$cartoon_qty);
            } else {
                $sale_price = floatval($cartoon_price/$cartoon_qty);
            }
        }
        
        $yesarray = array();
        if($getpst==1)
            {
        foreach($get_rules as $key => $single_p){
            
            if(is_numeric($key))
            {
                
                $mesurment_prod_term = $single_p['prod_term']; 
                $setruonarra = explode(',',$mesurment_prod_term);
             $getpst = get_option("save_price_filter_rules_plugin_active",true); 
    if($getpst==1){
                
                if($single_p['is_enable'] == 1){
                    
                    if($sale_price >= $single_p['me_price_min'] &&  $sale_price <= $single_p['me_price_max']){
                        
                    $terms2 = get_the_terms ($product->get_id(), 'product_cat');
                    
                    foreach($terms2 as $currntapgeid2)
                    {
                        $categorysfds = get_queried_object();
                $currntapgeid = $categorysfds->term_id;
                        if(empty($currntapgeid))
                        {
                            $currntapgeid = $currntapgeid2->term_id;
                        }
                        if (in_array($currntapgeid,$setruonarra))
                        {
                            
                            array_push($yesarray,'yes');
                            $apply_price = $single_p['mesurment_price'];
                            $apply_price_type = $single_p['me_price_type'];
                        }
                    }
                  }
                }
            }
                
            }
        }
        
        if (in_array('yes',$yesarray))
        {
            
            
                $sale_price = $product->get_sale_price();
                // if($taw_ver == 1 && $apply_price != 0){
                    $sale_price = $apply_price;
                    $sale_price_type = $apply_price_type;
                    
                // }
                $diff =  floatval($regular_price) - floatval($sale_price);
                $dis_perc = 100 -(($sale_price*100)/$regular_price);
            if(is_plugin_active('custom_margin/index.php')) 
            {       
                $get_rules2 = get_option("save_custom_margin_rules");
                $margintype = $get_rules2['margintype'];
                $newmargin = $get_rules2['newmargin'];
                if($margintype=='new' && !empty($newmargin))
                {
                $sale_price =  $sale_price/(1-($newmargin/100));
                $sale_price = number_format((float)$sale_price, 2, '.', '');
                }
        }
                
  echo  $price_box .= '<div style="width: 100%;" class="half-box onlistiapf"><h4>$'.$sale_price.$sale_price_type.'</h4></div>';
?>
<style>

    .half-box h4 {
        color: #9a0000;
        font-size: 15px;
        line-height: 29px;
    }
</style>
<?php
        
        }
        
        else {
            
            echo wpautop( divichild_get_meta( 'listing_short_meta_content' ),true);    
        }
        
            }
// }
            else
            {
             
           echo wpautop( divichild_get_meta( 'listing_short_meta_content' ),true);    
            }
            ?>
            
        </div>
        <style>div[itemprop="description"]{ display:none; }<?php if($hide == 'yes'){ echo 'li.product.post-'.$product->get_id().' span.onsale{ display:none;}'; } ?> div[itemprop="short-description"]{color: black; font-weight: normal;}</style>
        
    <?php
    if(isset($_GET['key']) && !empty($_GET['key'])){
     
        $tbl_meta = $wpdb->prefix."premmerce_wishlist_meta";
        $get_note = $wpdb->get_row("SELECT meta_value from $tbl_meta WHERE w_id='".$_GET['key']."' and meta_key = 'product_".get_the_ID()."'");
         echo "<div>";
         if(!is_user_logged_in()){
             $note = !empty($get_note) ? $get_note->meta_value : '';
             echo "<p class='note-p' style='font-weight: normal;'><b>Note:</b><span>".$note."</span></p>";
         } else {
                if(!empty($get_note) && isset($get_note->meta_value) && $get_note->meta_value != ''){
                    echo "<p class='note-p' style='font-weight: normal;'><a href='javascript:void(0);'><b>Note:</b><span>".$get_note->meta_value."</span><textarea style='display:none;' data-id='".get_the_ID()."'>".$get_note->meta_value."</textarea></a><a class='tesgsdgs' onclick=\"jQuery(this).closest('.note-p').find('textarea').show('slow'); jQuery(this).closest('.note-p').find('.save_note').show(); jQuery(this).hide()\" href='javascript:void(0);' >Edit Note</a><a class='save_note' onclick='myfunctionsavenote(this)' style='display:none;cursor: pointer;'>Save Note</a></p>";
                 } else {
                     echo "<p class='note-p' style='font-weight: normal;'><a href='javascript:void(0);'><b>Note:</b><textarea style='display:none;' data-id='".get_the_ID()."'></textarea></a><a class='tesgsdgs2' href='javascript:void(0);' onclick=\"jQuery(this).closest('.note-p').find('textarea').show('slow'); jQuery(this).closest('.note-p').find('.save_note').show(); jQuery(this).hide()\" >Add Note</a><a class='save_note' onclick='myfunctionsavenote(this)' style='display:none;cursor: pointer;'>Save Note</a></p>";
                 }
         }
         
         echo "</div>";
         ?>
         <script>
jQuery(document).ready(function(){
  jQuery( "a.tesgsdgs2,a.tesgsdgs" ).click(function() {
jQuery(this).siblings().each(function(){
    jQuery(this).find('textarea').show('slow');
    return false;
});
});
});
</script>
<?php
    }
}
?>