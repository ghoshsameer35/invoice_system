<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM invoice_list where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k=>$v){
            $$k= $v;
        }

        $qry_meta = $conn->query("SELECT i.*,s.name,s.description FROM invoice_services i inner join services_list s on i.service_id = s.id where i.invoice_id = '{$id}'");
    }
}
?>
<style>
    .select2-container--default .select2-selection--single{
        border-radius:0;
    }
    .cheque{
        display: none;
    }
    .online{
        display: none;
    }
    .cash{
        display: none;
    }
    /* .coupon{
        width: 25%;
        margin-left: auto;
    }
    .coupon1{
        width: 25%;
        margin-left: auto;
    } */
</style>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h5 class="card-title"><?php echo isset($id) ? "Update Invoice - ".$invoice_code : 'Create New Invoice' ?></h5>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="" id="invoice-form">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="col-md-12">
                    <fieldset class="border-bottom border-info">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="client_id" class="control-label text-info">Client</label>
                                <select name="client_id" id="client_id" class="custom-select custom-select-sm rounded-0 select2" data-placeholder="Please Select Client Here" required>
                                    <option <?php echo !isset($client_id) ? "selected" : '' ?> disabled></option>
                                    <?php 
                                    $client_qry = $conn->query("SELECT * FROM client_list where `status` = 1 ".(isset($client_id) && $client_id > 0 ? " OR id = '{$client_id}'":"")." order by fullname asc ");
                                    while($row = $client_qry->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($client_id) && $client_id == $row['id'] ? "selected" : '' ?>><?php echo $row['client_code'].' - '.$row['fullname'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border-bottom border-info">
                        <legend>Services</legend>
                        <div class="row align-items-end">
                            <div class="form-group col-sm-4">
                                <label for="service_id" class="control-label text-info">Service</label>
                                <select id="service_id" class="custom-select custom-select-sm rounded-0 select2" data-placeholder="Please Select Service Here">
                                    <option <?php echo !isset($service_id) ? "selected" : '' ?> disabled></option>
                                    <?php 
                                    $service_arr = array();
                                    $service_qry = $conn->query("SELECT * FROM services_list where `status` = 1 order by name asc ");
                                    while($row = $service_qry->fetch_assoc()):
                                        $service_arr[$row['id']] = $row;
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($service_id) && $service_id == $row['id'] ? "selected" : '' ?>><?php echo $row['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                               <button class="btn btn-flat btn-primary btn sm" type="button" id="add_to_list"><i class="fa fa-plus"></i> Add to List</button>
                            </div>
                        </div>
                        
                        <div class="form-group col-sm-4">
                                <label for="tenure" class="control-label text-info">Tenure</label>
                                <textarea class="form-control form-control-sm rounded-0" id="tenure" name="tenure" value="<?php echo isset($tenure) ? $tenure : '' ?>" required></textarea>
                            </div>
                       
                        <table class="table table-hover table-striped table-bordered" id="service-list">
                            <colgroup>
                                <col width="10%">
                                <col width="30%">
                                <col width="40%">
                                <col width="20%">
                            </colgroup>
                            <thead>
                                <tr class="bg-lightblue text-light">
                                    <th class="px-2 py-2 text-center"></th>
                                    <th class="px-2 py-2 text-center">Service</th>
                                    <th class="px-2 py-2 text-center">Description</th>
                                    <th class="px-2 py-2 text-center">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $total = 0;
                                if(isset($id)):
                                while($row = $qry_meta->fetch_assoc()):
                                    $total += $row['price'];
                            ?>
                                <tr>
                                    <td class="px-1 py-2 text-center align-middle">
                                        <button class="btn-sn btn-flat btn-outline-danger rem_btn" onclick="rem_row($(this))"><i class="fa fa-times"></i></button>
                                    </td>
                                    <td class="px-1 py-2 align-middle service">
                                        <span class="visible"><?php echo $row['name'] ?></span>
                                        <input type="hidden" name="service_id[]" value="<?php echo $row['service_id'] ?>">
                                        <input type="hidden" name="price[]" value="<?php echo $row['price'] ?>">
                                    </td>
                                    <td class="px-1 py-2 align-middle description"><?php echo $row['description'] ?></td>
                                    <td class="px-1 py-2 text-right align-middle price"><?php echo number_format($row['price'],2) ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                        Sub-total
                                    </th>
                                    <th class="px-2 py-2 text-right sub_total"><?php echo number_format($total,2) ?></th>
                                </tr>
                               
                                <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                        Discount
                                        <input type="number" style="width:50px" name="discount_perc" min="0" max="100"  value="<?php echo isset($discount_perc) ? $discount_perc : 0 ?>">
                                        <input type="hidden" name="discount" value="<?php echo isset($discount) ? $discount : 0 ?>">
                                        %
                                    </th>
                                    <th class="px-2 py-2 text-right discount"><?php echo isset($discount) ? number_format($discount,2) : "0.00" ?></th>
                                </tr>
                               
                                    <!-- change -->
                                <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                        CGST <small><i>(9%)</i></small>
                                        <input type="number" style="width:50px" name="tax_perc" min="0" max="100"  value="<?php echo isset($tax_perc) ? $tax_perc : 0 ?>">
                                        <input type="hidden" name="tax" value="<?php echo isset($tax) ? $tax : 0 ?>">
                                        %
                                    </th>
                                    <th class="px-2 py-2 text-right tax"><?php echo isset($tax) ? number_format($tax,2) : "0.00" ?></th>
                                </tr>
                                <!-- iggi -->
                                <!-- 1 -->
                                <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                        SGST<small><i>(9%)</i></small>
                                        <input type="number" style="width:50px" name="tax_perc1" min="0" max="100"  value="<?php echo isset($tax_perc1) ? $tax_perc1 : 0 ?>">
                                        <input type="hidden" name="tax1" value="<?php echo isset($tax1) ? $tax1 : 0 ?>">
                                        %
                                    </th>
                                    <th class="px-2 py-2 text-right tax1"><?php echo isset($tax1) ? number_format($tax1,2) : "0.00" ?></th>
                                </tr>
                                <!-- GFCJGF -->
                                <!-- 2 -->
                                <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                        IGST <small><i>(18%)</i></small>
                                        <input type="number" style="width:50px" name="tax_perc2" min="0" max="100"  value="<?php echo isset($tax_perc2) ? $tax_perc2 : 0 ?>">
                                        <input type="hidden" name="tax2" value="<?php echo isset($tax2) ? $tax2 : 0 ?>">
                                        %
                                    </th>
                                    <th class="px-2 py-2 text-right tax2"><?php echo isset($tax2) ? number_format($tax2,2) : "0.00" ?></th>
                                </tr>
                                <!-- END -->
                                 <!-- change -->

                                 <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                        Paid Amount
                                        <input type="number" style="width:50px" name="paid_amount_perc"   value="<?php echo isset($paid_amount_perc) ? $paid_amount_perc : 0 ?>">
                                        <input type="hidden" name="paid_amount" value="<?php echo isset($paid_amount) ? $paid_amount : 0 ?>">
                                        
                                    </th>
                                    <th class="px-2 py-2 text-right paid_amount"><?php echo isset($paid_amount) ? number_format($paid_amount,2) : "0.00" ?></th>
                                </tr>
                                    <!-- end -->
                                <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                    Due Bill
                                        <input type="hidden" name="total_amount" value="<?php echo isset($total_amount) ? $total_amount : 0 ?>">
                                    </th>
                                    <th class="px-2 py-2 text-right grand_total"><?php echo isset($total_amount) ? number_format($total_amount,2) : "0.00" ?></th>
                                </tr>
                                <!-- end -->
                                <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                    Total Amount
                                        <input type="hidden" name="due_bil" value="<?php echo isset($due_bil) ? $due_bil : 0 ?>">
                                    </th>
                                    <th class="px-2 py-2 text-right due_bil"><?php echo isset($due_bil) ? number_format($due_bil,2) : "0.00" ?></th>
                                </tr>
                            </tfoot>
                        </table>
                         <!-- change -->
                         <!-- <form action="" id="invoice-form1">
                         <tr class="bg-lightblue text-light disabled">
                                    <th class="px-2 py-2 text-right" colspan="3">
                                    <input type="text" id="form3Example1cg" class="form-control form-control-lg coupon " name="coupon" />
                        <label class="form-label" for="form3Example1cg">Coupon</label>
                                    </th>
                                </tr>
                                <div class="d-flex justify-content-center">
                        <button type="submit"
                      class="btn btn-success btn-block btn-lg gradient-custom-4 text-body coupon1" form="invoice-form1">Submit</button>
                        </div>
                                </form> -->

                                <!-- end -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="remarks" class="control-label text-info">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control rounded-0" rows="3" style="resize:none"><?php echo isset($remarks) ? $remarks : "" ?></textarea>
                            </div>
                            <!-- change -->
                            <div class="form-group col-sm-4">
                                <label for="amount_in_word" class="control-label text-info">Amount In Words</label>
                                <textarea class="form-control form-control-sm rounded-0" id="amount_in_word" name="amount_in_word" value="<?php echo isset($amount_in_word) ? $amount_in_word : '' ?>" required></textarea>
                            </div>
                            <!-- end -->
                            <div class="form-group col-md-6">
                                <label for="status" class="control-label text-info">Payment Status</label>
                                <select name="status" id="status" class="custom-select selevt">
                                    <!-- change -->
                                   
                                    <option value="0"class="cashButton"<?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Cash</option>
                                    <option value="1"class="onlineButton" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Online</option>
                                    <option value="2"class="chequeButton" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Cheque </option>
                                </select>
<!-- 
                                <script>
                                    const cheque = document.querySelector('.cheque')
                                    const chequeButton = document.querySelector('.chequeButton')
                                    cheque.classList.add('cheque')
                                    chequeButton.addEventListener('click',function(){
                                        cheque.classList.remove('cheque')
                                        cash.classList.add('cash')
                                        online.classList.add('online')
                                    })
                                    
                                    const online = document.querySelector('.online')
                                    const onlineButton = document.querySelector('.onlineButton')
                                    online.classList.add('online')
                                    onlineButton.addEventListener('click',function(){
                                        online.classList.remove('online')
                                        cash.classList.add('cash')
                                        cheque.classList.add('cheque')
                                    })
                                    const cash = document.querySelector('.cash')
                                    const cashButton = document.querySelector('.cashButton')
                                    cash.classList.add('cash')
                                    cashButton.addEventListener('click',function(){
                                        cash.classList.remove('cash')
                                        online.classList.add('online')
                                        cheque.classList.add('cheque')
                                    })
                                </script> -->

                                <!-- Cheque -->
                                <div class="form-group col-sm-4">
                                <label for="cheque" class="control-label text-info cheque">Cheque Numbar</label>
                                <input type="text" class="form-control form-control-sm rounded-0 cheque" id="cheque" name="cheque" value="<?php echo isset($cheque) ? $cheque : '' ?>">
                            </div>
                            <!-- UPI -->
                            <div class="form-group col-sm-4">
                                <label for="upi" class="control-label text-info online">UPI Transaction ID</label>
                                <input type="text" class="form-control form-control-sm rounded-0 online" id="upi" name="upi" value="<?php echo isset($upi) ? $upi : '' ?>">
                            </div>
                            <!-- cash -->
                            <div class="cash">
                                <div class="form-group col-sm-4">
                                <label for="two_thousand" class="control-label text-info">2000</label>
                                <input type="text" class="form-control form-control-sm rounded-0" id="two_thousand" name="two_thousand" value="<?php echo isset($two_thousand) ? $two_thousand : '' ?>">
                                </div>
                                <div class="form-group col-sm-4">
                                <label for="five_hundred" class="control-label text-info">500</label>
                                <input type="text" class="form-control form-control-sm rounded-0" id="five_hundred" name="five_hundred" value="<?php echo isset($five_hundred) ? $five_hundred : '' ?>">
                                </div>
                                <div class="form-group col-sm-4">
                                <label for="two_hundred" class="control-label text-info">200</label>
                                <input type="text" class="form-control form-control-sm rounded-0" id="two_hundred" name="two_hundred" value="<?php echo isset($two_hundred) ? $two_hundred : '' ?>">
                                </div>
                                <div class="form-group col-sm-4">
                                <label for="one_hundred" class="control-label text-info">100</label>
                                <input type="text" class="form-control form-control-sm rounded-0" id="one_hundred" name="one_hundred" value="<?php echo isset($one_hundred) ? $one_hundred : '' ?>">
                                </div>
                                <div class="form-group col-sm-4">
                                <label for="fifty" class="control-label text-info">50</label>
                                <input type="text" class="form-control form-control-sm rounded-0" id="fifty" name="fifty" value="<?php echo isset($fifty) ? $fifty : '' ?>">
                                </div>
                                <div class="form-group col-sm-4">
                                <label for="twenty" class="control-label text-info">20</label>
                                <input type="text" class="form-control form-control-sm rounded-0" id="twenty" name="twenty" value="<?php echo isset($twenty) ? $twenty : '' ?>">
                                </div>
                                <div class="form-group col-sm-4">
                                <label for="ten" class="control-label text-info">10</label>
                                <input type="text" class="form-control form-control-sm rounded-0" id="ten" name="ten" value="<?php echo isset($ten) ? $ten : '' ?>">
                                </div>
                                </div>
                            </div>
                           
                        </div>
                    </fieldset>
                </div>
            </form>           
        </div>
        <!-- change -->
        <!-- <div class="form-group col-sm-4">
                                <label for="coupon" class="control-label text-info">Apply Coupon</label>
                                <input type="text" class="form-control form-control-lg" name="coupon" id="coupon_str" />
                            </div>
                            <button class="btn btn-flat btn-sn btn-primary" type="button" name="submit" value="Apply Coupon" onclick="set_coupon()">Apply</button>
    </div> -->
    <!-- <form id="applyDiscountForm" method="post"
    action="invoice/manage_invoice.php?action=show_discount"
    onsubmit="return validate();">
    <div id="discount-grid">
            <div class="discount-section">
                <div class="discount-action">
                    <span id="error-msg-span" class="error-message">
                    <?php
                    if (! empty($message)) {
                        echo $message;
                    }
                    ?>
                    </span> <span></span><input type="text"
                        class="discount-code" id="discountCode"
                        name="discountCode" size="15"
                        placeholder="Enter Coupon Code" /><input
                        id="btnDiscountAction" type="submit"
                        value="Apply Discount" class="btnDiscountAction" />
                </div>
            </div>
        </div>

        </form>
        <script>
function validate() {
    var valid= true;
     if($("#discountCode").val() === "") {
        valid = false;
     }

     if(valid == false) {
         $('#error-msg-span').text("Discount Coupon Required");
     }
     return valid;
}
</script> -->

        <?php

require_once ("dbcontroller.php");
$db_handle = new DBController();

if (! empty($_GET["action"])) {
    switch ($_GET["action"]) {
        // case "add":
        //     if (! empty($_POST["quantity"])) {
        //         $productByCode = $db_handle->runQuery("SELECT * FROM cms_db WHERE code='" . $_GET["code"] . "'");
        //         $itemArray = array(
        //             $productByCode[0]["code"] => array(
        //                 'name' => $productByCode[0]["name"],
        //                 'code' => $productByCode[0]["code"],
        //                 'quantity' => $_POST["quantity"],
        //                 'price' => $productByCode[0]["price"],
        //                 'image' => $productByCode[0]["image"]
        //             )
        //         );
                
            //     if (! empty($_SESSION["cart_item"])) {
            //         if (in_array($productByCode[0]["code"], array_keys($_SESSION["cart_item"]))) {
            //             foreach ($_SESSION["cart_item"] as $k => $v) {
            //                 if ($productByCode[0]["code"] == $k) {
            //                     if (empty($_SESSION["cart_item"][$k]["quantity"])) {
            //                         $_SESSION["cart_item"][$k]["quantity"] = 0;
            //                     }
            //                     $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
            //                 }
            //             }
            //         } else {
            //             $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
            //         }
            //     } else {
            //         $_SESSION["cart_item"] = $itemArray;
            //     }
            // }
            // break;
        // case "remove":
        //     if (! empty($_SESSION["cart_item"])) {
        //         foreach ($_SESSION["cart_item"] as $k => $v) {
        //             if ($_GET["code"] == $k)
        //                 unset($_SESSION["cart_item"][$k]);
        //             if (empty($_SESSION["cart_item"]))
        //                 unset($_SESSION["cart_item"]);
        //         }
        //     }
        //     break;
        case "show_discount":
            
                if (! empty($_POST["discountCode"])) {
                    $priceByCode = $db_handle->runQuery("SELECT discunt FROM coupon WHERE coupon='" . $_POST["discountCode"] . "'");
                    
                    if (! empty($priceByCode)) {
                        foreach ($priceByCode as $key => $value) {
                            $discountPrice = $priceByCode[$key]["discunt"];
                        }
                        if (! empty($discountPrice) && $discountPrice > $_POST["totalPrice"]) {
                            $message = "Invalid Discount Coupon";
                        }
                    } else {
                        $message = "Invalid Discount Coupon";
                    }
                }
            echo test;
             
            break;
    }
}
?>



    <!-- <script>
			function set_coupon(){
				var coupon_str=jQuery('#coupon_str').val();
				if(coupon_str!=''){
					jQuery('#coupon_result').html('');
					jQuery.ajax({
						url:'set_coupon.php',
						type:'post',
						data:'coupon_str='+coupon_str,
						success:function(result){
							var data=jQuery.parseJSON(result);
							if(data.is_error=='yes'){
								jQuery('#coupon_box').hide();
								jQuery('#coupon_result').html(data.dd);
								jQuery('#order_total_price').html(data.result);
							}
							if(data.is_error=='no'){
								jQuery('#coupon_box').show();
								jQuery('#coupon_price').html(data.dd);
								jQuery('#order_total_price').html(data.result);
							}
						}
					});
				}
			}
		</script>	 -->
    <!-- end -->
    <div class="card-footer text-center">
        <button class="btn btn-flat btn-sn btn-primary" type="submit" form="invoice-form">Save</button>
        <a class="btn btn-flat btn-sn btn-dark" href="<?php echo base_url."admin?page=invoice" ?>">Cancel</a>
    </div>
</div>
<table id="tbl-clone" class="d-none">
    <tr>
        <td class="px-1 py-2 text-center align-middle">
            <button class="btn-sn btn-flat btn-outline-danger rem_btn"><i class="fa fa-times"></i></button>
        </td>
        <td class="px-1 py-2 align-middle service">
            <span class="visible"></span>
            <input type="hidden" name="service_id[]">
            <input type="hidden" name="price[]">
        </td>
        <td class="px-1 py-2 align-middle description"></td>
        <td class="px-1 py-2 text-right align-middle price"></td>
    </tr>
</table>

<!-- asdfg -->
<!-- <script>
	$("#ap").click(function(){
		if($('#coupon').val()!=''){
			$.ajax({
						type: "POST",
						url: "process.php",
						data:{
							coupon: $('#coupon').val()
						},
						success: function(dataResult){
							var dataResult = JSON.parse(dataResult);
							if(dataResult.statusCode==200){
								var after_apply=$('#total_price').val()-dataResult.value;
								$('#total_price').val(after_apply);
								$('#apply').hide();
								$('#edit').show();
								$('#message').html("Promocode applied successfully !");
								
							}
							else if(dataResult.statusCode==201){
								$('#message').html("Invalid promocode !");
							}
						}
			});
		}
		else{
			$('#message').html("Promocode can not be blank .Enter a Valid Promocode !");
		}
	});
	$("#edit").click(function(){
		$('#coupon').val("");
		$('#apply').show();
		$('#edit').hide();
		location.reload();
	});
</script> -->





<script>
    var services = $.parseJSON('<?php echo json_encode($service_arr) ?>');
    $(function(){
		$('.select2').select2({
			width:'resolve'
		})

        $('#invoice-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
             if($('#service-list tbody tr').length <= 0){
                 alert_toast("Please Add at least 1 Service on the List.","warning")
                 return false;
             }
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_invoice",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.href = _base_url_+"admin?page=invoice/view_invoice&id="+resp.id;
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

        // change
        $('input[name="discount_perc"],input[name="tax_perc"],input[name="tax_perc1"],input[name="tax_perc2"],input[name="paid_amount_perc"]').on('input',function(){
            calc()
        })
        $('#add_to_list').click(function(){
            var service_id = $('#service_id').val()
            if(service_id <= 0)
            return false;
            if($('#service-list tbody tr[data-id="'+service_id+'"]').length > 0){
                alert_toast("Service already exists on the list.","warning")
                return false;
            }
            var name = services[service_id].name || 'N/A';
            var description = services[service_id].description || 'N/A';
            var price = services[service_id].price || 'N/A';
            var tr = $('#tbl-clone tr').clone()
            tr.attr('data-id',service_id)
            tr.find('input[name="service_id[]"]').val(service_id)
            tr.find('input[name="price[]"]').val(price)
            tr.find('.service .visible').text(name)
            tr.find('.description').text(description)
            tr.find('.price').text(parseFloat(price).toLocaleString('en-US'))
            $('#service-list tbody').append(tr)
            $('#service_id').val('').trigger('change')
            calc()
            tr.find('.rem_btn').click(function(){
                rem_row($(this))
            })
        })
	})
    function rem_row(_this){
        _this.closest('tr').remove()
        calc()
    }
    function calc(){
        var sub_total = 0;
        var grand_total = 0;
        var discount = 0;
        var tax = 0;
        var tax1 = 0;
        var tax2 = 0;
        var paid_amount = 0;
        var due_bil = 0;
       

        $('#service-list tbody input[name="price[]"]').each(function(){
            sub_total += parseFloat($(this).val())
        })
        $('.sub_total').text(parseFloat(sub_total).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
        discount = sub_total * (parseFloat($('input[name="discount_perc"]').val())/100)
        $('.discount').text(parseFloat(discount).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
        $('input[name="discount"]').val(parseFloat(discount))
        tax = sub_total * (parseFloat($('input[name="tax_perc"]').val()) / 100)
        $('.tax').text(parseFloat(tax).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
        $('input[name="tax"]').val(parseFloat(tax))
        // kjhg
        tax1 = sub_total * (parseFloat($('input[name="tax_perc1"]').val()) / 100)
        $('.tax1').text(parseFloat(tax1).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
        $('input[name="tax1"]').val(parseFloat(tax1))
        // jhg///
        tax2 = sub_total * (parseFloat($('input[name="tax_perc2"]').val()) / 100)
        $('.tax2').text(parseFloat(tax2).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
        $('input[name="tax2"]').val(parseFloat(tax2))
        

        paid_amount = (sub_total - discount + tax2 + tax1 + tax)+ (parseFloat($('input[name="paid_amount_perc"]').val()) -(sub_total - discount + tax2 + tax1 + tax))
        $('.paid_amount').text(parseFloat(paid_amount).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
        $('input[name="paid_amount"]').val(parseFloat(paid_amount))


        // change
        grand_total =((sub_total - discount + tax2 + tax1 + tax)-paid_amount);
        $('.grand_total').text(parseFloat(grand_total).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
        $('input[name="total_amount"]').val(parseFloat(grand_total))
        
        due_bil = (sub_total - discount + tax2 + tax1 + tax);
        $('.due_bil').text(parseFloat(due_bil).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
        $('input[name="due_bil"]').val(parseFloat(due_bil))
    }
</script>