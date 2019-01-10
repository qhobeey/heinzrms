@extends('layouts.backend.heinz')

@section('links')
<link rel="stylesheet" type="text/css" href="/advanced/2/datables.min.css">
@endsection

@section('content')

<div class="content">
  <div class="row" style="border-bottom: 2px solid black;">
    <h1 style="font-weight: 600; text-align: center; text-transform: uppercase; color: black; font-size: 28px;"><?= env('ASSEMBLY_SMS_NAME'); ?></h1>
  </div>
  <div class="row" style="border-bottom: 2px solid black;">
    <h4 style="text-align: center; font-size: 14px; color: black; font-weight: 600;">Property Listing Grouped by <?= ucwords($location); ?> Area for <?= ucwords($year); ?></h4>
  </div>
<table id="fBill1" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
          <th></th>
          <th style="font-size: 11px;color: black;">Electoral Area</th>
          <th style="font-size: 11px;color: black;"></th>
          <th style="font-size: 11px;color: black;">Arrears</th>
          <th style="font-size: 11px;color: black;">Current Bill</th>
          <th style="font-size: 11px;color: black;">Total Bill</th>
          <th style="font-size: 11px;color: black;">Total Payment</th>
          <th style="font-size: 11px;color: black;">Outstanding Arrears</th>
        </tr>
    </thead>
    <tbody>
        @foreach($electorals as $electoral)
        <div class="tableInner">
          <tr class="odd2 heyy">
              <td>
                <a href="{{route('advanced.report.property.details', ['electoral', $electoral->code, $year])}}"><img src="/advanced/1/add-square-button.png"></a>
              </td>
              <td>
                <a style="color:blue; font-weight: 400;" href="{{route('advanced.report.property.details', ['electoral', $electoral->code, $year])}}">
                  <?= $electoral->description; ?>&nbsp; [<?= $electoral->count_bills; ?>]
                </a>
              </td>
              <td></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney($electoral->bills_arrears, true); ?></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney($electoral->current_bills, true); ?></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney(($electoral->bills_arrears + $electoral->current_bills), true); ?></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney($electoral->total_paid_bills, true); ?></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney(($electoral->bills_arrears + $electoral->current_bills) - $electoral->total_paid_bills, true); ?></td>
          </tr>
        </div>
        @endforeach
    </tbody>
    <tfoot class="foot">
      <div class="tableInner">
          <tr class="odd2 heyy">
              <td></td>
              <td>

              </td>
              <td></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney($electorals->sum('bills_arrears'), true); ?></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney($electorals->sum('current_bills'), true); ?></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney(($electorals->sum('bills_arrears') + $electorals->sum('current_bills')), true); ?></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney($electorals->sum('total_paid_bills'), true); ?></td>
              <td><?= \App\Repositories\ExpoFunction::formatMoney(($electorals->sum('bills_arrears') + $electorals->sum('current_bills') - $electorals->sum('total_paid_bills')), true); ?></td>
          </tr>
        </div>

    </tfoot>

</table>
{{$electorals->appends(request()->query())->links()}}
</div>


@endsection

@section('scripts')

<script src="/advanced/2/datables.min.js" charset="utf-8"></script>

<script>

$(document).ready(function() {

    var table = $('#fBill1').DataTable({
        'responsive': true,
        'bPaginate': false,
        'searching': false,
        'paging': false,
        'info': false

    });

    // Add event listener for opening and closing details
    $('#fBill1 tbody').on('click', 'td.details-control', function(){
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if(row.child.isShown()){
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });

    // Handle click on "Expand All" button
    $('#btn-show-all-children').on('click', function(){
        // Enumerate all rows
        table.rows().every(function(){
            // If row has details collapsed
            if(!this.child.isShown()){
                // Open this row
                this.child(format(this.data())).show();
                $(this.node()).addClass('shown');
            }
        });
    });

    // Handle click on "Collapse All" button
    $('#btn-hide-all-children').on('click', function(){
        // Enumerate all rows
        table.rows().every(function(){
            // If row has details expanded
            if(this.child.isShown()){
                // Collapse row details
                this.child.hide();
                $(this.node()).removeClass('shown');
            }
        });
    });

    document.querySelector('.back').style.display = "none";
});
</script>
@endsection
