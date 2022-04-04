<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT i.*,c.fullname,c.address,c.company_name,c.email,c.contact,c.gender,c.website FROM invoice_list i inner join client_list c on i.client_id = c.id where i.id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k=>$v){
            $$k= $v;
        }

        $qry_meta = $conn->query("SELECT i.*,s.name,s.description FROM invoice_services i inner join services_list s on i.service_id = s.id where i.invoice_id = '{$id}'");
    
        
    }
    
}
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h5 class="card-title">Invoice Details</h5> <br>
        
        
    </div>
    <div class="card-body">
        <div class="container-fluid" id="print_out">
            <style>
                @media print{
                    .bg-lightblue {
                        background-color: #3c8dbc !important;
                    }
                }
                body {
                -webkit-print-color-adjust: exact !important;
                }
            </style>
            <h3>Digital Spider Pvt Ltd</h3>  
            <h5>33A J L Nehru Road, Chatterjee International Center 20th floor <br> Kolkata-700071</h5>
            <h5>Phone : (+91) 8100781955/033 3544 2401</h5>
            <h5>E-mail: customer-care@thedigitalspider.com</h5>
            <h5>Website: www.thedigitalspider.com</h5>
            <h5>PAN:AAICD9817F / GSTIN:</h5>
            <!-- kugjgf -->
            <h3 class="text-info">Invoice: <b><?php echo isset($invoice_code) ? $invoice_code :'' ?></b></h3>
            <fieldset class="border-bottom border-info">
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="client_id" class="control-label text-info">Client</label>
                        <div><b><?php echo strtoupper($fullname) ?></b></div>
                    </div>
                    <!-- chang -->
                    
                   

                    <div class="form-group col-sm-4">
                        <label for="client" class="control-label text-info">Client Address</label>
                        <div><?php echo isset($address) ? $address : "" ?></div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="client" class="control-label text-info">Company Name</label>
                        <div><?php echo isset($company_name) ? $company_name : "" ?></div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="client" class="control-label text-info">email</label>
                        <div><?php echo isset($email) ? $email : "" ?></div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="client" class="control-label text-info">Contact</label>
                        <div><?php echo isset($contact) ? $contact : "" ?></div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="client" class="control-label text-info">PanNo/GST:</label>
                        <div><?php echo isset($gender) ? $gender : "" ?></div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="client" class="control-label text-info">Tenure</label>
                        <div><?php echo isset($tenure) ? $tenure : "" ?></div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="client" class="control-label text-info">Website</label>
                        <div><?php echo isset($website) ? $website : "" ?></div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="border-bottom border-info">
                <legend>Services</legend>
                <table class="table table-hover table-striped table-bordered" id="service-list">
                    <colgroup>
                        <col width="10%">
                        <col width="30%">
                        <col width="40%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr class="bg-lightblue text-light" style="background: #3c8dbc !important;">
                            <th class="px-2 py-2 text-center">#</th>
                            <th class="px-2 py-2 text-center">Service</th>
                            <th class="px-2 py-2 text-center">Description</th>
                            <th class="px-2 py-2 text-center">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $total = 0;
                        while($row = $qry_meta->fetch_assoc()):
                            $total += $row['price'];
                        ?>
                            <tr>
                                <td class="px-1 py-2 text-center align-middle"><?php echo $i++; ?></td>
                                <td class="px-1 py-2 align-middle service"><?php echo $row['name'] ?></td>
                                <td class="px-1 py-2 align-middle description"><?php echo $row['description'] ?></td>
                                <td class="px-1 py-2 text-right align-middle price"><?php echo $row['price'] ?></td>
                            </tr>
                        <?php endwhile; ?>
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
                                Discount (<?php echo isset($discount_perc) ? $discount_perc : 0 ?>%)
                            </th>
                            <th class="px-2 py-2 text-right discount"><?php echo isset($discount) ? number_format($discount,2) : 0 ?></th>
                        </tr>
                       
                        <!-- change -->
                        <tr class="bg-lightblue text-light disabled">
                            <th class="px-2 py-2 text-right" colspan="3">
                            CGST<small><i></i></small>
                                (<?php echo isset($tax_perc) ? $tax_perc : 0 ?>%)
                            </th>
                            <th class="px-2 py-2 text-right tax"><?php echo isset($tax) ? number_format($tax,2) : 0 ?></th>
                        </tr>
                        <!-- iggi -->
                        <!-- 1 -->
                        <tr class="bg-lightblue text-light disabled">
                            <th class="px-2 py-2 text-right" colspan="3">
                                SGST<small><i></i></small>
                                (<?php echo isset($tax_perc1) ? $tax_perc1 : 0 ?>%)
                            </th>
                            <th class="px-2 py-2 text-right tax1"><?php echo isset($tax1) ? number_format($tax1,2) : 0 ?></th>
                        </tr>

                             <!-- GFCJGF -->
                            <!-- 2 -->
                            <tr class="bg-lightblue text-light disabled">
                            <th class="px-2 py-2 text-right" colspan="3">
                                IGST<small><i></i></small>
                                (<?php echo isset($tax_perc2) ? $tax_perc2 : 0 ?>%)
                            </th>
                            <th class="px-2 py-2 text-right tax2"><?php echo isset($tax2) ? number_format($tax2,2) : 0 ?></th>
                        </tr>
                        <!-- end -->

                        <tr class="bg-lightblue text-light disabled">
                            <th class="px-2 py-2 text-right" colspan="3">
                         <b>Total Amount</b>
                            </th>
                            <th class="px-2 py-2 text-right grand_total"><?php echo isset($due_bil) ? number_format($due_bil,2) : 0 ?></th> 
                        </tr>
                        <!-- end -->
                    </tfoot>
                </table>
                 <!-- hghghg -->
                        <tr class="bg-lightblue text-light disabled" style="padding-right: 30rem;">
                            <th class="px-2 py-2 text-right" colspan="3">
                          <h6>  <b> Paid Amount</b>
                          <!-- <?php echo isset($paid_amount_perc) ? $paid_amount_perc : 0 ?> -->
                            </th>
                            <th class="px-2 py-2 text-right paid_amount"><?php echo isset($paid_amount) ? number_format($paid_amount,2) : 0 ?></th></h6>
                        </tr>
                            <!-- end -->
                            <br>
                            <!-- utkhg -->

                            <tr class="bg-lightblue text-light disabled">
                            <th class="px-2 py-2 text-right" colspan="3">
                         <h6>  <b>Due Bill</b>
                            </th>
                            <th class="px-2 py-2 text-right grand_total"><?php echo isset($total_amount) ? number_format($total_amount,2) : 0 ?></th> </h6>
                        </tr>
                            <!-- end -->
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="remarks" class="control-label text-info">Remarks</label>
                        <p><?php echo isset($remarks) ? $remarks : "N/A" ?></p>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="status" class="control-label text-info">Payment Method</label>
                        <div class="pl-4">
                            <!-- change -->
                            <!-- elseif -->
                            <?php if($status == 0): ?>
                                <span class="badge badge-pill badge-success">Cash</span>
                                <?php elseif($status == 1): ?>
                                <span class="badge badge-pill badge-success">ONline</span>
                            <?php else: ?>
                                <span class="badge badge-pill badge-success">Cheque</span>
                            <?php endif; ?>
                        </div>
                        <div class="test">
                            <P>
                                Make all Cheque payable to <br>
                                <B>  DIGITAL SPIDER PVT LTD </B>
                                <br>
            <h6>This is Computer Generated Invoice Signature Not Required</h6>
                            </p>
                        </div>
                        
                    </div>
                    </div>
                    <div class="form-group col-sm-4 amount">
                        <label for="client" class="control-label text-info" >Amount In Words</label>
                        <div><?php echo isset($amount_in_word) ? $amount_in_word : "" ?></div>
                </div>
<div>
   

<style>
    .test {
        margin-left:290px !important
    }
    .test{
        margin-top:-9rem !important
    }
    .test{
        margin-bottom:1rem !important
    }
    .amount {
    margin-left: -0.5rem !important
}

    
</style>


</div>
            </fieldset>
            <div class="subject"style="margin-left:21rem;">
            <u><h5>Subject to Calcutta Jurisdiction Only</h5></u>
            
            </div>
            <p>
           <h3> TERMS & CONDITIONS OF SERVICES </h3><br>
           <h5>1. GENERAL </h5>
1.1 The Terms & Conditions contained herein shall constitute and from an entire Agreement (hereinafter referred to as 'Agreement between DIGITAL SPIDER And the
Customer'. <br>
1.2 Any Clause of The Terms & Conditions if deemed invalid, void or for any reason becomes unenforceable, shall be deemed severable and shall not affect 
thevalidity and the enforceability of the remaining clauses of the conditions of this agreement.
<h5>2. SERVICES, EXCLUSIONS & Performance</h5>
2.1 In the event the advertisement requirements requested by the customersfall within the restricted category of Google or are not supported by Google or any
one against the policy of Google. <br>
2.2 DIGITAL SPIDER reserves the right to refuse or cancel any advertising requirement at its sole discretion, with or without cause, at any time, 
balance advertising budget will not be refunded to the customer. <br>
2.3 Service Contract for Google Ad word, Which is paid from of advertising on Google. Customer's website link would appear under sponsored link on Google
search result page. <br>
Our Template based website solution would not be given along with FTP (File Transfer Protocol).
DIGITAL SPIDER Will Start on any contract only if 60%of contract amount is paid in advance, Rest of amount should to be paid on before completion of work.
2.4 Customershould not ask for any work report or claim any dispute of contract if the contract period is over, terminated or lapsed(contract period should
Calculate from the day of contract signed or payment done to DIGITAL SPIDER which ever is later). <br>
<h5>3. CONSIDERATION </h5>
3.1 The considerations mean the cost of the package, purchased by the customer from DIGITAL SPIDER. <br>
3.2 DIGITAL SPIDER reserves the right to charge for any additional work executed by DIGITAL SPIDER.<br>
3.3 The cost of click would include DIGITAL SPIDER charges over above the actual cost of click on Google Ad words.<br>
3.4 In the event the customer agree to pay the consideration for the services via ECS mode, than the same cannot be cancelled by the customer amidst the terms
of the agreement, Unlessthe Agreement is earlier terminated by DIGITAL SPIDER at its sole discretion or by mutual consent of DIGITAL SPIDER and the Customer.<br>
<h5>4. INDEMNITY </h5>
4.1 Customer shall indemnity and hold DIGITAL SPIDER harmless from all claims, costs, Proceedings, Damages and expenses(Including legal and other professional
fees and
expenses), Awarded against or paid by DIGITAL SPIDER as a result of or in connection with any alleged or actual infringement of any third party's intellectual
property right (including copyright) or other right arising out of the use or supply of the information by or on behalf of the customer to the DIGITAL SPIDER. <br>
<h5>5. TERMINATION </h5>
If the contract isterminated by the customers before services under this Agreement are to begin executions or are in the
process of completion that in such an event , Under no circumstances, of the consideration paid or agreed to be the Customer, Shall not 
berefundable and the same shall not be forfeited in full.
Any Token Money paid to DIGITAL SPIDER against contract shall not be refundable in any case. <br>
<h5>6. MISCELLANEOUS </h5>
6.1 DIGITAL SPIDER shall be permitted to identify customer, as DIGITAL SPIDER client and may use Customer’s name in connection with DIGITAL SPIDER marketing
initiative. <br>
6.2 Customer agrees and permits DIGITAL SPIDER to make calls and messages on his mobile and office contact numberssubsequent to the signing of this agreement. <br>
6.3 DIGITAL SPIDER is authorized to replicates the existing website of Customer on its sub-domain and his hereby authorized to make such changes as may 
berequired for the betterment of the delivery of the advertising campaign. <br>
<h5>7. DISCLAIMER </h5>
7.1 DIGITAL SPIDER makes no representatives, Warranties or guarantees of any kind asto the level of sales, purchase, click, sales leads or other performance that
customer
Can expect from advertising campaign through DIGITAL SPIDER. Any estimated provided by DIGITAL SPIDER to the Customer are not intended to create any binding
obligation or to be relied upon by the customer and the same are mere estimates. <br>
7.2 DIGITAL SPIDER will not be liable for any loss of profit, loss of contract, loss of use, or any direct and/or indirect and/ or any consequential loss, damage 
andexpenses sustained incurred by the customer and the customer as a result of any acts or omission or information or advise/given in any form by or on 
behalf ofDIGITAL SPIDER to the customer and the customer is advised to make its own inquiries and use its own judgment and/ or intellect before taking any
decision
regarding the same. <br>
7.3 In addition to the above it is further agreed that the customer shall be solely liable for any loss or damage, whether monetary or other suffered by it, as a
result of any change effected by its own in the website by using CMS and DIGITAL SPIDER shall not be held liable on any account whatsoever. <br>
<h5>8 FORCE MAJEURE </h5>
8.1 Neither Party will be liable to the other, for any delay or failure to fulfill obligationsset for till in this Agreement caused by force major reasons or
Circumstances beyond their control. <br>
<h5> 9. COMMUNICATION </h5>
9.1 any notice send by the customer with respect to this Agreement has be in writing and hasto be sent registered post at the following address: Chatterjee 
International Centre, 33 A, J.L.Nehru Road,20 TH Floor, Room No - 2,Park Street,Kolkata-700071. <br>
9.2 In case of any query the customer can contact the manager of the DIGITAL SPIDER between 10 AM to 6 PM between Monday to Friday on the phone no. Given
on the face of the present Invoice. <br>
<h5>10. GOVERNING LAW & JURISDICTION </h5>
10.1 The Agreement, its validity, construction, Interpretation, effect, performance and termination shall by the laws(both substantive and procedural) as
Applicable in INDIA from time to time. 
    </p>
        </div>
    </div>
    <div class="card-footer text-center">
            <button class="btn btn-flat btn-sn btn-success" type="button" id="print"><i class="fa fa-print"></i> Print</button>
            <a class="btn btn-flat btn-sn btn-primary" href="<?php echo base_url."admin?page=invoice/manage_invoice&id=".$id ?>"><i class="fa fa-edit"></i> Edit</a>
            <a class="btn btn-flat btn-sn btn-dark" href="<?php echo base_url."admin?page=invoice" ?>">Back to List</a>
    </div>
</div>
<script>
    $(function(){
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Invoice Details - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light")
            p.find('tr.bg-lightblue').removeClass("bg-lightblue")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="165px" height="95px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Invoice</h4>'+
                      '</div>'+
                      '<div class="col-1 text-right">'+
                      '</div>'+
                      '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes")
                     nw.document.write(_el.html())
                     nw.document.close()
                     setTimeout(() => {
                         nw.print()
                         setTimeout(() => {
                            nw.close()
                            end_loader()
                         }, 200);
                     }, 500);
        })
    })
</script>