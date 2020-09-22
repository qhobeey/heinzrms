@extends('layouts.backend.heinz')

@section('content')

<div class="content">
  <div class="card search-card">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#menuZonal">⎮Zonal⎮</a></li>
      <li><a data-toggle="tab" href="#menuElectoral">⎮Electoral⎮</a></li>
      <li><a data-toggle="tab" href="#menuTas">⎮Town Area⎮</a></li>
      <li><a data-toggle="tab" href="#menuCommunity">⎮Community⎮</a></li>
      <li><a data-toggle="tab" href="#menuType">⎮Business Type⎮</a></li>
    </ul>
    <div class="tab-content" style="padding-top: 0px;">
      <div id="menuZonal" class="tab-pane fade in active">
        <h3 style="font-size: 19px; text-transform: uppercase;margin-left: 10px; margin-right: 10px;text-align:center;">Business by Sub Metro</h3>
        <hr style="border-top: 1px solid #ebeff2;margin-bottom: 10px;margin-top: 10px;width: 76%;">
        <form class="" action="#" method="get">
          @csrf
          <div class="row search-cont">

            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Year</label>
                <select class="form-control" name="year" required>
                  <?php
                    for ($i=date('Y'); $i>2006; $i--) {?>
                      <option value="<?= $i; ?>"><?= $i; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <input type="hidden" name="location" value="zonal">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Zonal</label>
                <select class="form-control" name="loc">
                  <option value="a"><?php echo strtoupper('all zonals'); ?></option>
                  <?php
                    $elts = \App\Models\Location\Zonal::orderBy('code', 'asc')->get();
                    foreach ($elts as $data) {?>
                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="form-control btn btn-xs">Preview</button>
            </div>
          </div>
        </form>
      </div>
      <div id="menuElectoral" class="tab-pane fade">
        <h3 style="font-size: 19px; text-transform: uppercase;margin-left: 10px; margin-right: 10px;text-align:center;">Business by Electoral Area</h3>
        <hr style="border-top: 1px solid #ebeff2;margin-bottom: 10px;margin-top: 10px;width: 76%;">
        <form class="" action="{{route('advanced.report.business')}}" method="get">
          @csrf
          <div class="row search-cont">

            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Year</label>
                <select class="form-control" name="year" required>
                  <?php
                    for ($i=date('Y'); $i>2006; $i--) {?>
                      <option value="<?= $i; ?>"><?= $i; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <input type="hidden" name="location" value="electoral">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Electoral</label>
                <select class="form-control" name="loc">
                  <option value="a"><?php echo strtoupper('all electorals'); ?></option>
                  <?php
                    $elts = \App\Models\Location\Electoral::orderBy('code', 'asc')->get();
                    foreach ($elts as $data) {?>
                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="form-control btn btn-xs">Preview</button>
            </div>
          </div>
        </form>
      </div>
      <div id="menuTas" class="tab-pane fade">
        <h3 style="font-size: 19px; text-transform: uppercase;margin-left: 10px; margin-right: 10px;text-align:center;">Business by Town Area</h3>
        <hr style="border-top: 1px solid #ebeff2;margin-bottom: 10px;margin-top: 10px;width: 76%;">
        <form class="" action="#" method="get">
          @csrf
          <div class="row search-cont">

            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Year</label>
                <select class="form-control" name="year" required>
                  <?php
                    for ($i=date('Y'); $i>2006; $i--) {?>
                      <option value="<?= $i; ?>"><?= $i; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <input type="hidden" name="location" value="town area council">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Town Area</label>
                <select class="form-control" name="loc">
                  <option value="a"><?php echo strtoupper('all town area'); ?></option>
                  <?php
                    $elts = \App\Models\Location\Ta::orderBy('code', 'asc')->get();
                    foreach ($elts as $data) {?>
                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="form-control btn btn-xs">Preview</button>
            </div>
          </div>
        </form>
      </div>
      <div id="menuCommunity" class="tab-pane fade">
        <h3 style="font-size: 19px; text-transform: uppercase;margin-left: 10px; margin-right: 10px;text-align:center;">Business by Community</h3>
        <hr style="border-top: 1px solid #ebeff2;margin-bottom: 10px;margin-top: 10px;width: 76%;">
        <form class="" action="{{route('advanced.report.business')}}" method="get">
          @csrf
          <div class="row search-cont">

            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Year</label>
                <select class="form-control" name="year" required>
                  <?php
                    for ($i=date('Y'); $i>2006; $i--) {?>
                      <option value="<?= $i; ?>"><?= $i; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <input type="hidden" name="location" value="community">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Community</label>
                <select class="form-control" name="loc">
                  <option value="a"><?php echo strtoupper('all communities'); ?></option>
                  <?php
                    $elts = \App\Models\Location\Community::orderBy('code', 'asc')->get();
                    foreach ($elts as $data) {?>
                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="form-control btn btn-xs">Preview</button>
            </div>
          </div>
        </form>
      </div>
      <div id="menuType" class="tab-pane fade">
        <h3 style="font-size: 19px; text-transform: uppercase;margin-left: 10px; margin-right: 10px;text-align:center;">Business by Type</h3>
        <hr style="border-top: 1px solid #ebeff2;margin-bottom: 10px;margin-top: 10px;width: 76%;">
        <form class="" action="" method="get">
          @csrf
          <div class="row search-cont">

            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Year</label>
                <select class="form-control" name="year" required>
                  <?php
                    for ($i=date('Y'); $i>2006; $i--) {?>
                      <option value="<?= $i; ?>"><?= $i; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <input type="hidden" name="location" value="type">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Enter Business Type</label>
                <select class="form-control" name="loc">
                  <?php
                    $typs = \App\BusinessType::orderBy('code', 'asc')->get();
                    foreach ($typs as $data) {?>
                      <option value="<?= $data->code; ?>"><?= $data->description; ?></option>
                    <?php }?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="form-control btn btn-xs">Preview</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>


@endsection

@section('scripts')


@endsection
