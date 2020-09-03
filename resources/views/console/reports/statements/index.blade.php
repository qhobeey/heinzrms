@extends('layouts.backend.heinz')

@section('content')

<div class="content">
  <div class="card search-card">
    <div class="tab-content" style="padding-top: 0px;">
      <div id="menuProperty" class="tab-pane fade in active">
        <h3 style="font-size: 19px; text-transform: uppercase;margin-left: 10px; margin-right: 10px;text-align:center;">Account Statement</h3>
        <hr style="border-top: 1px solid #ebeff2;margin-bottom: 10px;margin-top: 10px;width: 76%;">
        <form class="" action="{{route('statements.preview')}}" method="get">
          @csrf
          <div class="row search-cont">


            <div class="col-md-12">
              <div class="form-group">
                <label for="">Account No</label>
                <input type="text" name="account" class="form-control" value="" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Start Date</label>
                <input type="date" name="startdate" required class="form-control" value="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">End Date</label>
                <input type="date" name="enddate" required class="form-control" value="">
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
