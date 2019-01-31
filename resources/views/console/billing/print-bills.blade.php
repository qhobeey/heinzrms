@extends('layouts.backend.heinz')

@section('content')
<?php
    session_start();
    use App\WebClientPrint\WebClientPrint;
?>

<div class="content">
    <div class="container">

        <div id="mainContent" class="card-box table-responsive">
            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                  <form class="heiz-dashboard-forms" style="width:80%;margin:auto;">
                    <div class="row">
                      <div class="col-md-4">
                        <input style="width:auto;" type="radio" @click="toggleCheck()" name="sortType" value="noFilter" checked> <label for="">No Filtering</label>
                      </div>
                      <div class="col-md-4">
                        <input style="width:auto;" type="radio" @click="toggleCheck()" name="sortType" value="filter"> <label for="">Filter Report</label>
                      </div>
                    </div>

                    <hr style="margin-top: 20; margin-bottom: 10px;">
                    <h4 class="form-desc">Filter By Location</h4>
                    <hr style="margin-top: 0; margin-bottom: 10px;">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Sub Metro</label>
                                <select :disabled="disableFilter" class="form-control" style="display: block; width: 200px; height: 26px;" name="zonal">
                                  <option value="">None</option>
                                  <?php
                                    $subs = \App\Models\Location\Zonal::orderBy('code', 'asc')->get();
                                    foreach ($subs as $sub) {?>
                                      <option value="<?= $sub->code; ?>"><?= $sub->description; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Electoral Area</label>
                                <select :disabled="disableFilter" class="form-control" style="display: block; width: 200px; height: 26px;" name="electoral">
                                  <option value="">None</option>
                                  <?php
                                    $elts = \App\Models\Location\Electoral::orderBy('code', 'asc')->get();
                                    foreach ($elts as $data) {?>
                                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Town Area Council</label>
                                <select :disabled="disableFilter" class="form-control" style="display: block; width: 200px; height: 26px;" name="tas">
                                  <option value="">None</option>
                                  <?php
                                    $tas = \App\Models\Location\Ta::orderBy('code', 'asc')->get();
                                    foreach ($tas as $data) {?>
                                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="padding-top: 20px;">
                                <label for="">Community</label>
                                <select :disabled="disableFilter" class="form-control" style="display: block; width: 200px; height: 26px;" name="community">
                                  <option value="">None</option>
                                  <?php
                                    $cmts = \App\Models\Location\Community::orderBy('code', 'asc')->get();
                                    foreach ($cmts as $data) {?>
                                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="padding-top: 20px;">
                                <label for="">Street</label>
                                <select :disabled="disableFilter" class="form-control" style="display: block; width: 200px; height: 26px;" name="street">
                                  <option value="">None</option>
                                  <?php
                                    $sts = \App\Models\Location\Street::orderBy('code', 'asc')->get();
                                    foreach ($sts as $data) {?>
                                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr style="margin-top: 20; margin-bottom: 10px;">
                    <h4 class="form-desc">Bill Info</h4>
                    <hr style="margin-top: 0; margin-bottom: 10px;">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Bill Year</label>
                                <select class="form-control" style="display: block; width: 200px; height: 26px;" name="year">
                                  <?php
                                    for ($i=date('Y'); $i>2009; $i--) {?>
                                      <option value="<?= $i; ?>"><?= $i; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Number of Bills </label>
                                <input type="text" disabled="true" style="height: 26px;" class="form-control" name="billNumbers" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <button type="button" @click.prevent="getBillCount()" class="form-control btn btn-danger btn-xs" style="font-size: 14px;width: 120px;margin-top: 25px; height: 26px; padding-top: 2px;">Get Count</button>
                            </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <input type="checkbox" name="" class="form-control" value="">
                            <label for="">Do not print bills with Zero(0) Rate Imposed</label>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4">
                          <button @click.prevent="issuePrint()" type="button" class="form-control btn btn-primary btn-xs" style="font-size: 14px;width: 120px;margin-top: 25px;">Print bills</button>
                        </div>
                      </div>



                  </form>
                </div>

            </div>

        </div>

        <div id="cardcont" style="display:none;">
          <div id="card" style="width: 1000px; height: 100%; padding-top: 50px; padding-bottom: 20px; border-radius: 0px; background-color:white; margin-top: 60px; margin-bottom: 50px;">
              <div style="width: 804px; background-color:white; margin:auto;">
                <div style="background-color:white; width:800px; display: flex; flex-direction: row;">
                  <img src="/images/assemblies/OFFINSOLOGO.jpg" style="width: 80px; height: 80px; object-fit: contain; margin: auto;">
                  <div style="width: 640px; border: 2px solid black; height: 100%; margin: auto;">
                    <h3 style="text-align: center; font-size: 25px; font-weight: 600; color: black; text-transform: uppercase;">Offinso Municipal Assembly</h3>
                  </div>
                  <img src="/images/assemblies/ghanacoatofarms.jpg" style="width: 80px; height: 80px; object-fit: contain; margin: auto;">
                </div>
                <h2 style="text-align: center; font-size: 26px; margin-top: 0px; margin-bottom: 0px; font-weight: 500; text-transform: uppercase; color: black;">Property Rate</h2>
                <hr style="margin-top: 0px; margin-bottom: 20px; width: 65%; border-top: 2px solid black;">
                <div style="background-color: white; width:800px; display: flex;">
                  <div style="background-color: white; width: 400px;">
                    <article style="width:100%; display:flex;">
                      <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Account No:</p>
                      <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 600;">&nbsp;&nbsp;&nbsp;AAS4444</p>
                    </article>
                    <article style="width:100%; display:flex; height: 96px; padding-top: 20px;">
                      <p style="color: black;margin-bottom: 10px;margin-top: 10px;font-size: 18px; font-weight: 600;">MT. CALVSRY SCH.</p>
                    </article>
                    <article style="width:100%; display:flex;">
                      <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Property Type:</p>
                      <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 300;">&nbsp;&nbsp;&nbsp;COMMERCIAL PROPERTY RATE</p>
                    </article>
                    <article style="width:100%; display:flex;">
                      <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Property Cat:</p>
                      <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 300;">&nbsp;&nbsp;&nbsp;PRIVATE SCHOOL</p>
                    </article>

                    <div style="background-color:white; width:80%; border:2px solid black; margin-top: 15px; padding-left: 10px; padding-bottom: 10px;">
                      <article style="width:100%; display:flex; justify-content: space-between; margin-top: 5px;">
                        <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Rateable value:</p>
                        <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 300;">GHc 37,500.00&nbsp;&nbsp;</p>
                      </article>
                      <article style="width:100%; display:flex; justify-content: space-between; margin-top: 15px;">
                        <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Rate Imposed:</p>
                        <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 300;">0.006&nbsp;&nbsp;</p>
                      </article>
                    </div>
                    <div style="background-color:white; display:block; width: 100%;">
                      <div style="background-color:white; display:flex; padding-top: 30px;">
                        <h4 style="font-size: 15px; font-weight: 600; color:black;">Stamp:&nbsp;&nbsp;&nbsp;</h4>
                        <img src="/images/assemblies/bdasign.jpg" style="width: 180px; height: 40px; object-fit: contain;">
                      </div>
                      <hr style="margin-top: 2px; margin-bottom: 2px; border-top: 2px dashed black; width: 80%; margin-left: inherit;">
                      <p style="text-transform: uppercase; font-size: 10px; width: 80%; color:black; text-align: center; font-weight:500;">district cordinating director</p>
                    </div>
                  </div>
                  <div style="background-color: white; width: 400px;">
                    <article style="width:100%; display:flex;">
                      <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Zones:</p>
                      <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 600;">&nbsp;&nbsp;&nbsp;&nbsp;NEW TOWN ZONAL COUNCIL</p>
                    </article>
                    <article style="width:100%; display:flex;">
                      <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Electoral Area:</p>
                      <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 600;">&nbsp;&nbsp;&nbsp;&nbsp;TWUMASEN</p>
                    </article>
                    <article style="width:100%; display:flex;">
                      <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Town Area Council:</p>
                      <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 600;">&nbsp;&nbsp;&nbsp;&nbsp;OBUASE</p>
                    </article>
                    <article style="width:100%; display:flex;">
                      <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Street:</p>
                      <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 600;">&nbsp;&nbsp;&nbsp;&nbsp;NO NAME</p>
                    </article>
                    <p style="font-size: 13px; font-weight: 600; color: black; margin-top: 10px; margin-bottom:0px;">All bills must be settled on or before 30-Jun-2016</p>
                    <p style="font-size: 13px; font-weight: 600; color: black; margin-top: 10px;">For enquires contact the Metro Cordinating Director on the ff Nos. 0267555557/0260772926</p>
                    <div style="background-color:white; width:100%; border:2px solid black; margin-top: 15px; padding-left: 10px; padding-bottom: 20px;">
                      <article style="width:100%; display:flex; justify-content: space-between; margin-top: 5px;">
                        <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Previous Year Bill:</p>
                        <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 300;">GHc 0.00&nbsp;&nbsp;</p>
                      </article>
                      <article style="width:100%; display:flex; justify-content: space-between; margin-top: 5px;">
                        <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Amount Paid(Last Yr):</p>
                        <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 300;">GHc 0.00&nbsp;&nbsp;</p>
                      </article>
                      <article style="width:100%; display:flex; justify-content: space-between; margin-top: 5px;">
                        <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Arrears:</p>
                        <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 300;">GHc 0.00&nbsp;&nbsp;</p>
                      </article>
                      <article style="width:100%; display:flex; justify-content: space-between; margin-top: 5px;">
                        <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Current Fee:</p>
                        <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 300;">GHc 225.00&nbsp;&nbsp;</p>
                      </article>
                      <article style="width:100%; display:flex; justify-content: space-between; margin-top: 30px;padding-top: 5px; border-top: 2px solid black; border-bottom: 2px solid black; padding-bottom: 5px; background: antiquewhite;">
                        <p style="color: black;margin-bottom: 0px;width: 40%;font-size: 13px; font-weight: 600;">Total Amount Due Fee:</p>
                        <p style="color: black;margin-bottom: 0px;font-size: 13px; font-weight: 600;">GHc 225.00&nbsp;&nbsp;</p>
                      </article>
                    </div>
                  </div>
                </div>
                <div style="background-color:white; width: 100%; font-size: 11px; color: black; margin-top:20px;">
                  <p>
                    The Asante Akin South District Assembly (A.A.S.D.A) is vested with the power to collect Property Rate in the Asante Akim South District Assembly
                    by the provision of Section 34,77,78,94, and 98 of Local Government Act, 1993 (Act 462) and other relevant Bye Laws. In the event of failure to
                    comply with the demand of this notice, A.A.S.D.A shall be liable to Civil Prosecution for the recovery of the outstanding amount plus interest.
                  </p>
                  <h4 style="color: black; text-transform: uppercase; font-weight: 600; text-align: center; margin-top: 13px; font-size: 22px;">payment should be made with the bill</h4>
                  <h5 style="text-align: center; text-transform: uppercase; font-weight: 600; color: black; font-size: 16px; letter-spacing: 2px;">pay your bills promptly and help the city clean</h5>
                  <hr style="border-top: 2px dashed black;">
                </div>

              </div>
          </div>

          <div class="row"  style="width: 84%; margin: auto;">

            <div id="loadPrinters">
              Click to load and select one of the installed printers!
              <br />
              <button type="button" class="btn btn-danger" onclick="javascript:jsWebClientPrint.getPrinters();">Load installed printers...</button>
            </div>
            <div id="installedPrinters" style="visibility:hidden">
                <label for="installedPrinterName">Select an installed Printer:</label>
                <select name="installedPrinterName" class="form-control" style="width: 50%;" id="installedPrinterName"></select>
            </div>
            <div class="row" id="printDevice">
              <button type="button" class="btn btn-success" id="printBtn">Issue Print Command</button>
            </div>
          </div>

        </div>
    </div>
</div>






@endsection
@section('scripts')
{!! $wcpScript; !!}
<script type="text/javascript">
    var wcppGetPrintersTimeout_ms = 10000; //10 sec
    var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

    function wcpGetPrintersOnSuccess() {
        // Display client installed printers
        if (arguments[0].length > 0) {
            var p = arguments[0].split("|");
            var options = '';
            for (var i = 0; i < p.length; i++) {
                options += '<option>' + p[i] + '</option>';
            }
            $('#installedPrinters').css('visibility', 'visible');
            $('#installedPrinterName').html(options);
            $('#installedPrinterName').focus();
            $('#loadPrinters').hide();
        } else {
            alert("No printers are installed in your system.");
        }
    }
    function wcpGetPrintersOnFailure() {
        // Do something if printers cannot be got from the client
        alert("No printers are installed in your system.");
    }
</script>
<script>
    var app = new Vue({
      el: '#heinz',
      data: {
        disableFilter: true
      },
      methods: {
        toggleCheck() {
          var sortType = document.querySelector("input[name=sortType]:checked").value
          this.disableFilter =  (sortType == "noFilter") ? true : false;
        },
        getBillCount() {
          if (this.disableFilter == true) {
            var requestParams = {
              year: document.querySelector("select[name=year]").value,
              isFilter: false
            }
          }else{
            var requestParams = {
              year: document.querySelector("select[name=year]").value,
              zonal: document.querySelector("select[name=zonal]").value,
              electoral: document.querySelector("select[name=electoral]").value,
              tas: document.querySelector("select[name=tas]").value,
              community: document.querySelector("select[name=community]").value,
              street: document.querySelector("select[name=street]").value,
              isFilter: true
            }
          }
          axios.get('/api/get/bill/count', { params: requestParams })
                .then(response => document.querySelector("input[name=billNumbers]").value = response.data.data)
                .catch(error => console.error(error));
        },
        issuePrint() {
          document.getElementById('mainContent').style.display = "none"
          document.getElementById('cardcont').style.display = "block"
        }
      },
      created() {
        this.toggleCheck()
      }


    });
</script>

<script type="text/javascript">
  function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
  }
</script>

<script type="text/javascript">
    var wcppGetPrintersTimeout_ms = 10000; //10 sec
    var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

    function wcpGetPrintersOnSuccess() {
        // Display client installed printers
        if (arguments[0].length > 0) {
            var p = arguments[0].split("|");
            var options = '';
            for (var i = 0; i < p.length; i++) {
                options += '<option>' + p[i] + '</option>';
            }
            $('#installedPrinters').css('visibility', 'visible');
            $('#installedPrinterName').html(options);
            $('#installedPrinterName').focus();
            $('#loadPrinters').hide();
        } else {
            alert("No printers are installed in your system.");
        }
    }
    function wcpGetPrintersOnFailure() {
        // Do something if printers cannot be got from the client
        alert("No printers are installed in your system.");
    }
</script>
<script>
$(document).ready(function(){
    $("#printBtn").click(function () {

      html2canvas($('#card'), {
        onrendered: function(canvas)
        {
          var b64Prefix = "data:image/png;base64,";
          var imgBase64DataUri = canvas.toDataURL("image/jpg");
          var imgBase64Content = imgBase64DataUri.substring(b64Prefix.length, imgBase64DataUri.length);

          //2. save image base64 content to server-side Application Cache
          $.ajax({
              type: "POST",
              url: "/api/StoreImageFileController",
              data: { base64ImageContent : imgBase64DataUri},
              success: function (imgFileName) {
                  //alert("The image file: " + imgFileName + " was created at server side. Continue printing it...");

                  //2. Print the stored image file specifying the created file name
                  jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '&imageFileName=' + imgFileName);
              }
          });
        }
       });

    });
});
</script>
@endsection
