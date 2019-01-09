@extends('layouts.backend.heinz')

@section('content')

<div class="content">
  <div class="card search-card">
    <form class="" action="{{route('advanced.report.property')}}" method="get">
      @csrf
      <div class="row search-cont">
        <h3 style="font-size: 21px; margin-left: 10px; margin-right: 10px;text-align:center;">Property by Electoral Area</h3>
        <hr style="border-top: 1px solid #36404a;margin-bottom: 16px;margin-top: 10px;">
        <div class="col-md-12">
          <div class="form-group">
            <label for="">Enter Year</label>
            <select class="form-control" name="year" required>
              <?php
                for ($i=date('Y')-1; $i>2017; $i--) {?>
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
                $elts = \App\Models\Location\Electoral::latest()->get();
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
</div>


@endsection

@section('scripts')


@endsection
