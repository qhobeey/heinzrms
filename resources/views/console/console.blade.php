@extends('layouts.backend.heinz')

@section('content')
<div class="content">
    <div class="container">

        <div class="row heinz-dashboard-nav-cards">
            <div class="col-md-6 col-lg-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="{{route('stock.index')}}">
                        <h3 class="text-white">Value Books</h3>
                        <button class="text-white">
                          <span style="font-size:20px;">Total</span><br>
                          <?php $vbooks = \App\Stock::latest()->get(); echo $vbooks->count(); ?>
                        </button>
                        <div class="row c-stats">
                          <h5>Enum GCRs:
                            <span><?php $vbooks = \App\EnumGcr::latest()->get(); echo $vbooks->count(); ?></span>
                          </h5>

                          <h5 class="t-align-right">Issued VB:
                            <span><?php $vbooks = \App\IssueStock::latest()->get(); echo $vbooks->count(); ?></span>
                          </h5>
                        </div>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="#">
                        <h3 class="text-white">Property Rates</h3>
                        <button class="text-white">
                          <span style="font-size:20px;">Total</span><br>
                          <?php $property = \App\Property::latest()->get(); echo $property->count(); ?>
                        </button>
                        <div class="row c-stats">
                          <h5>Total revenue:
                            <span><?php
                              $count = 0.0;
                              $dataType = "p";
                              $datas = \App\Payment::where('data_type', 'LIKE', "%{$dataType}%")->latest()->get();
                              foreach ($datas as $data) {
                                $count += $data->amount_paid;
                              }
                              echo "GHc". \App\Repositories\ExpoFunction::formatMoney($count, true);
                             ?></span>
                          </h5>

                          <h5 class="t-align-right">Today stats:
                            <span><?php
                              $count = 0;
                              $datas = \App\Property::latest()->get();
                              foreach ($datas as $data) {
                                if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                                if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):
                                  $count++;
                                endif;
                              }
                              echo $count;
                             ?></span>
                          </h5>

                          <h5>Today's revenue:
                            <span><?php
                              $count = 0.0;
                              $dataType = "p";
                              $datas = \App\Payment::where('data_type', 'LIKE', "%{$dataType}%")->latest()->get();
                              foreach ($datas as $data) {
                                if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                                if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):

                                endif;
                              }
                              echo "GHc". \App\Repositories\ExpoFunction::formatMoney($count, true);
                             ?></span>
                          </h5>

                          <h5 class="t-align-right">Estimated revenue:
                            <span><?php
                              $dataType = "p";
                              $count = \App\Bill::where('bill_type', 'LIKE', "%{$dataType}%")->sum('current_amount');

                              echo "GHc". \App\Repositories\ExpoFunction::formatMoney($count, true);
                             ?></span>
                          </h5>
                        </div>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="#">
                        <h3 class="text-white">Building Permit</h3>
                        <button class="text-white">
                          <span style="font-size:20px;">Total</span><br>
                          NA
                        </button>
                        <div class="row c-stats">
                          <h5>Total revenue:
                            <span>NA</span>
                          </h5>

                          <h5 class="t-align-right">Today stats:
                            <span>NA</span>
                          </h5>

                          <h5>Today's revenue:
                            <span>NA</span>
                          </h5>
                        </div>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="#">
                        <h3 class="text-white">Business</h3>
                        <button class="text-white">
                          <span style="font-size:20px;">Total</span><br>
                          <?php $business = \App\Business::latest()->get(); echo $business->count(); ?>
                        </button>
                        <div class="row c-stats">
                          <h5>Total revenue:
                            <span><?php
                              $count = 0.0;
                              $dataType = "b";
                              $datas = \App\Payment::where('data_type', 'LIKE', "%{$dataType}%")->latest()->get();
                              foreach ($datas as $data) {
                                $count += $data->amount_paid;
                              }
                              echo "GHc". \App\Repositories\ExpoFunction::formatMoney($count, true);
                             ?></span>
                          </h5>

                          <h5 class="t-align-right">Today stats:
                            <span><?php
                              $count = 0;
                              $datas = \App\Business::latest()->get();
                              foreach ($datas as $data) {
                                if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                                if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):
                                  $count++;
                                endif;
                              }
                              echo $count;
                             ?></span>
                          </h5>

                          <h5>Today's revenue:
                            <span><?php
                              $count = 0.0;
                              $dataType = "b";
                              $datas = \App\Payment::where('data_type', 'LIKE', "%{$dataType}%")->latest()->get();
                              foreach ($datas as $data) {
                                if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                                if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):

                                endif;
                              }
                              echo "GHc".$count;
                             ?></span>
                          </h5>

                          <h5 class="t-align-right">Estimated revenue:
                            <span><?php
                              $dataType = "b";
                              $count = \App\Bill::where('bill_type', 'LIKE', "%{$dataType}%")->sum('current_amount');

                              echo "GHc". \App\Repositories\ExpoFunction::formatMoney($count, true);
                             ?></span>
                          </h5>
                        </div>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="#">
                        <h3 class="text-white">Marriage</h3>
                        <button class="text-white">
                          <span style="font-size:20px;">Total</span><br>
                          NA
                        </button>
                        <div class="row c-stats">
                          <h5>Total revenue:
                            <span>NA</span>
                          </h5>

                          <h5 class="t-align-right">Today stats:
                            <span>NA</span>
                          </h5>

                          <h5>Today's revenue:
                            <span>NA</span>
                          </h5>
                        </div>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="#">
                        <h3 class="text-white">Other</h3>
                        <button class="text-white">
                          <span style="font-size:20px;">Total</span><br>
                          NA
                        </button>
                        <div class="row c-stats">
                          <h5>Total revenue:
                            <span>NA</span>
                          </h5>

                          <h5 class="t-align-right">Today stats:
                            <span>NA</span>
                          </h5>

                          <h5>Today's revenue:
                            <span>NA</span>
                          </h5>
                        </div>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <!-- <form class="stats-form">
          <fieldset>
            <legend>Total's statistics</legend>
            <div class="row current-stats">
              <div class="col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="">
                        <h4>Property Data</h4>
                        <button>
                          <span style="font-size:20px;">Current</span><br>
                          <?php
                            $count = 0;
                            $datas = \App\Property::latest()->get();
                            foreach ($datas as $data) {
                              if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                              if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):
                                $count++;
                              endif;
                            }
                            echo $count;
                           ?>
                        </button>
                        <h4><?= \Carbon\Carbon::now()->toFormattedDateString(); ?></h4>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="">
                        <h4>Business Data</h4>
                        <button>
                          <span style="font-size:20px;">Current</span><br>
                          <?php
                            $count = 0;
                            $datas = \App\Business::latest()->get();
                            foreach ($datas as $data) {
                              if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                              if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):
                                $count++;
                              endif;
                            }
                            echo $count;
                           ?>
                        </button>
                        <h4><?= \Carbon\Carbon::now()->toFormattedDateString(); ?></h4>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="">
                        <h4>Payment Data</h4>
                        <button>
                          <span style="font-size:20px;">Current</span><br>
                          <?php
                            $count = 0;
                            $datas = \App\Payment::latest()->get();
                            foreach ($datas as $data) {
                              if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                              if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):
                                $count++;
                              endif;
                            }
                            echo $count;
                           ?>
                        </button>
                        <h4><?= \Carbon\Carbon::now()->toFormattedDateString(); ?></h4>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="">
                        <h4>Revenue</h4>
                        <button>
                          <span style="font-size:20px;">Current</span><br>
                          <?php
                            $count = 0.0;
                            $datas = \App\Payment::latest()->get();
                            foreach ($datas as $data) {
                              if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                              if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):
                                $count += $data->amount_paid;
                              endif;
                            }
                            echo "GHc".$count;
                           ?>
                        </button>
                        <h4><?= \Carbon\Carbon::now()->toFormattedDateString(); ?></h4>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="">
                        <h4>Used GCRs</h4>
                        <button>
                          <span style="font-size:20px;">Current</span><br>
                          <?php
                            $count = 0;
                            $datas = \App\Payment::latest()->get();
                            foreach ($datas as $data) {
                              if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                              if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):
                                $count ++;
                              endif;
                            }
                            echo $count;
                           ?>
                        </button>
                        <h4><?= \Carbon\Carbon::now()->toFormattedDateString(); ?></h4>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="widget-bg-color-icon card-box fadeInDown animated">
                    <div class="text-center">
                        <a href="">
                        <h4>SMS</h4>
                        <button>
                          <span style="font-size:20px;">Current</span><br>
                          <?php
                            $count = 0;
                            $datas = \App\InstantSMS::latest()->get();
                            foreach ($datas as $data) {
                              if ($data->created_at == "" || $data->created_at == NULL || $data->created_at == "NULL") continue;
                              if($data->created_at->toDateString() == \Carbon\Carbon::now()->toDateString()):
                                $count ++;
                              endif;
                            }
                            echo $count;
                           ?>
                        </button>
                        <h4><?= \Carbon\Carbon::now()->toFormattedDateString(); ?></h4>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </fieldset>
        </form> -->
    </div>
</div>
@endsection
