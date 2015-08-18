<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table id="module" class="form">
            <tr>
              <td class="left"><?php echo $text_api_key; ?></td>
              <td class="left">
                <input type="text" name="giftd_api_key" value="<?php echo $giftd_api_key ?>" size="20" />
              </td>
            </tr>
            <tr>
              <td class="left"><?php echo $text_user_id; ?></td>
              <td class="left">
                <input type="text" name="giftd_user_id" value="<?php echo $giftd_user_id ?>" size="20" />
              </td>
            </tr>
            <tr class="add_params">
              <td class="left"><?php echo $text_partner_code; ?></td>
              <td class="left">
                <input type="text" name="giftd_partner_code" value="<?php echo $giftd_partner_code ?>" size="20" />
              </td>
            </tr>
            <tr class="add_params">
              <td class="left"><?php echo $text_prefix; ?></td>
              <td class="left">
                <input type="text" name="giftd_prefix" value="<?php echo $giftd_prefix ?>" size="20" />
                <input type="hidden" name="giftd_code_updated" value="<?php echo $giftd_code_updated ?>" size="20" />
                <textarea style="display: none;" cols="" name="giftd_js_code" rows=""><?php echo $giftd_js_code ?></textarea>
              </td>
            </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function check_data(){
    var api_key = $('input[name="giftd_api_key"]').val();
    var user_id = $('input[name="giftd_user_id"]').val();
    
    if((api_key != '') && (user_id != '')){
        $(".add_params").show();
    }else{
        $(".add_params").hide();
    }
}

$(document).ready(function(){
    check_data()
});

$('input[name="giftd_api_key"]').blur(function(){
    check_data()
});

$('input[name="giftd_user_id"]').blur(function(){
    check_data()
});
//--></script> 
<?php echo $footer; ?>