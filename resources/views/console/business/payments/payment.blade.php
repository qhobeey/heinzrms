@extends('layouts.backend.heinz')

@section('content')
<div class="content">
    <div class="container">

        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal heiz-dashboard-forms" role="form" action="{{route('property.payments.payment')}}" id="addTable" method="post" enctype="multipart/form-data" data-parsley-validate="" novalidate="">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Property No</label>
                                        <select name="property_id" v-model="pno" id="" class="form-control" @blur="getPBills(pno);">
                                            <option value="">-choose-</option>
                                            <template v-for="data in properties">
                                                <option :value="data.id">@{{data.property_no}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Arrears</label>
                                        <input type="text" :value="bill.arrears" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Current Amount</label>
                                        <input type="text" v-if="bill.current_amount == 0.0" :value="f_message" disabled class="form-control">
                                        <input type="text" v-else :value="bill.current_amount" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Net Balance</label>
                                        <input type="text" disabled class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row amount-due">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label style="margin-top: 10px;" for="">Collector</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select name="collector_id" v-model="collector_id" id="" @blur="getCStocks(collector_id);" class="form-control">
                                                    <option value="">-choose-</option>
                                                    <template v-for="data in collectors">
                                                        <option :value="data.id">@{{data.name}}</option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label style="margin-top: 10px;" for="">Cashier</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select name="cashier_id" id="" class="form-control">
                                                    <option value="">-choose cashier-</option>
                                                    <template v-for="data in cashiers">
                                                        <option :value="data.id">@{{data.name}}</option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="form-desc">Payment Details</h4>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Amount Paid</label>
                                        <input type="text" name="amount_paid" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">GCR No</label>
                                        <select name="gcr_number" name="gcr_number" id="" class="form-control">
                                            <template v-for="data in gcrs">
                                                <option :value="data.id">@{{data.gcr_number}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Payment Mode</label>
                                        <select name="payment_mode" id="" class="form-control">
                                            <option value="">-select-</option>
                                            <option value="cash">Cash</option>
                                            <option value="cheque">Cheque</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Date Paid</label>
                                        <input type="date" v-model="date" name="payment_date" id="" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Collectors Payment Receipt No</label>
                                        <input type="text" name="cprn" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <button type="submit" class="form-control btn btn-primary">Add Entry</button>
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
<script>
    
    var app = new Vue({
      el: '#heinz',
      data: {
        f_message: 'Full Payment',
        pno: '',
        properties: [],
        bill: [],
        collectors: [],
        collector_id: '',
        cashiers: [],
        date: new Date().toJSON().slice(0,10),
        gcrs: []
      },
      methods: {
          getPBills(query){
            axios.get(`/api/v1/console/get_property_bills/${query}`)
                 .then(response => {console.log(response.data), this.bill = response.data.bill})
                 .catch(error => console.error(error));
          },
          getCStocks(query){
            axios.get(`/api/v1/console/get_collectors_stock/${query}`)
                 .then(response => {console.log(response.data), this.gcrs = response.data.data})
                 .catch(error => console.error(error));
          }
      },
      created(){
        axios.get('/api/v1/console/get_properties')
            .then(response => this.properties = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_collectors')
            .then(response => this.collectors = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_cashiers')
            .then(response => this.cashiers = response.data.data)
            .catch(error => console.error(error));
      }
        

    });
</script>
@endsection