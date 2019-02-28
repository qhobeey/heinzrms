@extends('layouts.backend.heinz')

@section('content')
<div class="content">
    <div class="container">
        <div class="card-box table-responsive">
            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-6">
                      <h3 style="color: brown; font-size: 20px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Account List (<?php echo count($bills); ?>)</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                      @if ($errors->has('account_box'))
                          <small class="invalid-feedback">
                              <strong style="color:red;">{{ $errors->first('account_box') }}</strong>
                          </small>
                      @endif
                      <form class="" action="{{route('lgt.property.sms')}}" method="post">
                        @csrf
                        <input type="hidden" name="type" value="p">
                        <section style="max-height: 365px;overflow: auto;">
                          <table class="table table-striped table-bordered dt-responsive fixed">
                              <thead style="font-size: 12px;">
                                  <tr role="row">
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 30px;">
                                        <input type="checkbox" onclick="toggle(this);" name="allBox" value="all">
                                      </th>
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 91px;">Account No</th>
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending"style="width: 45px;">Type</th>
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending">Rate PA</th>
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending">Current Amt</th>
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending">Arrears</th>
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending">Balance</th>
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending">Total Pmt</th>
                                      <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 65px;">Year</th>
                                  </tr>
                              </thead>
                              <tbody style="font-size: 11px;">
                                  @foreach($bills as $bill)
                                  <tr role="row" class="odd">
                                      <td class="sorting_1" tabindex="0">
                                        <input type="checkbox" name="account_box[]" value="<?php echo $bill->account_no; ?>">
                                      </td>
                                      <td class="sorting_1" tabindex="0"><a href="#"><?php echo $bill->account_no; ?></a></td>
                                      <td class="sorting_1" tabindex="0"><a href="#"><?php echo $bill->bill_type; ?></a></td>
                                      <td class="sorting_1" tabindex="0"><a href="#"><?php echo $bill->rate_pa; ?></a></td>
                                      <td class="sorting_1" tabindex="0"><a href="#"><?php echo $bill->current_amount; ?></a></td>
                                      <td class="sorting_1" tabindex="0"><a href="#"><?php echo $bill->arrears; ?></a></td>
                                      <td class="sorting_1" tabindex="0"><a href="#"><?php echo $bill->account_balance; ?></a></td>
                                      <td class="sorting_1" tabindex="0"><a href="#"><?php echo $bill->total_paid; ?></a></td>
                                      <td class="sorting_1" tabindex="0"><a href="#"><?php echo $bill->year; ?></a></td>

                                  </tr>

                                  @endforeach

                              </tbody>
                          </table>
                        </section>
                        <section class="row" style="margin-top:20px;">
                          <!-- skldk;flks;dlfks;d -->
                        </section>
                      </form>
                    </div>
                    <div class="col-md-3">
                      <h4 class="fp">Filter Parameters</h4>
                      <hr style="margin-top: 10px;margin-bottom: 5px;">
                      <form class="filterbox" action="{{route('lgt.property.bills.bulk.query')}}" method="get">
                        @csrf
                        <div class="form-group">
                          <label for="">Zonal</label>
                          <?php $zonals = App\Models\Location\Zonal::orderBy('description', 'asc')->get(); ?>
                          <select disabled class="form-control" name="">
                            <option value="">SELECT</option>
                            <?php foreach ($zonals as $key => $zonal): ?>
                              <option value="<?php echo $zonal->code; ?>"><?php echo $zonal->description; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="">Electoral</label>
                          <?php $electorals = App\Models\Location\Electoral::orderBy('description', 'asc')->get(); ?>
                          <select class="form-control" name="electoral_id">
                            <option value="">SELECT</option>
                            <?php foreach ($electorals as $key => $electoral): ?>
                              <option value="<?php echo $electoral->code; ?>"><?php echo $electoral->description; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="">Town Area</label>
                          <?php $tas = App\Models\Location\Ta::orderBy('description', 'asc')->get(); ?>
                          <select disabled class="form-control" name="">
                            <option value="">SELECT</option>
                            <?php foreach ($tas as $key => $ta): ?>
                              <option value="<?php echo $ta->code; ?>"><?php echo $ta->description; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="">Community</label>
                          <?php $communities = App\Models\Location\Community::orderBy('description', 'asc')->get(); ?>
                          <select disabled class="form-control" name="">
                            <option value="">SELECT</option>
                            <?php foreach ($communities as $key => $community): ?>
                              <option value="<?php echo $community->code; ?>"><?php echo $community->description; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="">Bill Type</label>
                          <select class="form-control" name="bill_type" required>
                              <option value="p">Property</option>
                              <option value="b">Business</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="">Bill year</label>
                          <select class="form-control" name="year" required>
                            <?php
                              for ($i=date('Y'); $i>2017; $i--) {?>
                                <option value="<?= $i; ?>"><?= $i; ?></option>
                              <?php }?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="">Account No</label>
                          <input style="width:100%;height:30px;" type="text" class="form-control" name="account_no" value="">
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary btn-sm">Filter Query</button>
                        </div>
                      </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
@section('scripts')

<script type="text/javascript">
function toggle(source) {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i] != source)
          checkboxes[i].checked = source.checked;
  }
}
</script>

@endsection
