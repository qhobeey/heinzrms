@extends('layouts.backend.heinz')

@section('content')
<div class="content">
    <div class="container">

        <div class="card-box table-responsive">
            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-8">
                        <form class="" action="{{route('account.bills')}}" method="post">
                          @csrf
                          <div class="row">
                            <div class="col-md-3">
                              <div class="form-group">
                                <label for="">use fee fixing</label>
                                <select style="width: 100%;" class="form-control" name="feefixing">
                                  <option value="2017">2017</option>
                                  <option selected value="2018">2018</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label for="">for year</label>
                                <select style="width: 100%;" class="form-control" name="year">
                                  <option value="2017">2017</option>
                                  <option selected value="2018">2018</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label for="">for account</label>
                                <select style="width: 100%;" class="form-control" name="account">
                                  <option value="property">property</option>
                                  <option value="business">business</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <button type="submit" style="margin-top: 25px;" class="btn btn-danger">Generate account bills</button>
                              </div>
                            </div>
                          </div>
                        </form>
                    </div>

                    <div class="col-sm-6">

                    </div>

                </div>
                <div class="row">
                    <form class="" action="{{route('setups.property.bills.sms')}}" method="post">
                      @csrf
                      <div class="col-sm-12" style="height: 296px; overflow: auto;">
                          <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
                              <thead>
                                  <tr role="row">
                                      <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 10px;">
                                        <input type="checkbox" onclick="toggle(this);" name="allAccount" value="1">
                                      </th>
                                      <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 91px;">Account no</th>
                                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Type</th>
                                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Category</th>
                                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Owner</th>
                                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Amount</th>
                                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Arrears</th>
                                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Bill year</th>
                                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Telephone</th>
                                  </tr>
                              </thead>
                              <tbody>

                              </tbody>

                          </table>

                      </div>

                      <!-- <div class="row">
                        <p>Total Bills: <span style="color: #f24e4b;">calculating...</span> </p>
                      </div> -->

                      <div class="row">
                        <div class="col-md-4">
                          <button type="submit" class="btn btn-danger" name="button" style="border-radius: inherit;top: 12px;position: relative;">Send Property Bill SMS to Owners</button>
                        </div>
                      </div>

                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
@section('scripts')
<script>
    var app = new Vue({
      el: '#heinz',
      data: {
          properties: [],
          name: '',
          checkedNames: ''
      },
      methods: {
          getname (id) {
              var results = '';
            axios.get(`/api/v1/console/get_property_owner/${1}`)
                .then(response => {
                    console.log(response.data.data);
                    results = response.data.data.firstname + ' ' + response.data.data.lastname;
                    this.name = results
                }).catch(error => console.error(error));
                console.log(this.name);
                return this.name;
          }
      },
      created() {

        // axios.get('/api/v1/console/get_properties_d/')
        //     .then(response => this.properties = response.data.data)
        //     .catch(error => console.error(error));
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
@endsection
