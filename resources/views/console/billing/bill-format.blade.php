@extends('layouts.backend.heinz')

@section('content')

<div id="card" style="width: 70%; margin:auto; height: 100%; padding-top: 50px; padding-bottom: 20px; border-radius: 0px; background-color:white; margin-top: 100px; margin-bottom: 100px;">
  <div class="row" style="width:90%; margin:auto;">
    <form class="" action="{{route('customize.bill.format')}}" enctype="multipart/form-data" method="post">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Payment Due Date For all Bills</label>
            <input type="date" class="form-control" name="paymet_date" value="<?= $setting->paymet_date; ?>">
            <small> Default: <span style="color:brown;"><?= \Carbon\Carbon::parse($setting->paymet_date)->toFormattedDateString();?></span></small>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Cordinators Numbers</label>
            <input type="text" class="form-control" name="contact_info_text" value="<?= $setting->contact_info_text; ?>">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Cordinator Position</label>
            <input type="text" class="form-control" name="authority_person" value="<?= $setting->authority_person; ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Organization Type</label>
            <select class="form-control" name="organization_type" value="">
              <option disabled="true" selected="true" value="<?= $setting->organization_type; ?>"><?= $setting->organization_type; ?></option>
              <option value="Metro">Metropolitan</option>
              <option value="District">District</option>
              <option value="Municipal">Municipal</option>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Assembly Logo</label>
            <input type="file" class="form-control" name="assembly_logo">
            <div style="margin: auto; width: 30%; margin-top:10px;">
              <img src="<?= $setting->logo ?: '/images/assemblies/OFFINSOLOGO.jpg'; ?>" style="width: 80px; height: 80px; object-fit: contain; margin: auto;">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Assembly Signature</label>
            <input type="file" class="form-control" name="assembly_signature">
            <div style="margin: auto; width: 30%; margin-top:10px;">
              <img src="<?= $setting->signature ?: '/images/assemblies/bdasign.jpg'; ?>" style="width: 80px; height: 80px; object-fit: contain; margin: auto;">
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="">Law Info</label>
            <textarea class="form-control" name="enforce_law_text" rows="8" cols="80"><?= $setting->enforce_law_text; ?></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <button type="submit" class="btn btn-danger">Save</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')

{!!

// Register the WebClientPrint script code
// The $wcpScript was generated by PrintHtmlCardController@index

$wcpScript;

!!}

@endsection
