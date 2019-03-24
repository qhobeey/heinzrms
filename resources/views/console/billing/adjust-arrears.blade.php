@extends('layouts.backend.heinz')

@section('content')

<div class="content">
  <div class="card search-card">
    <div class="tab-content" style="padding-top: 0px;">
      <div id="menuProperty" class="tab-pane fade in active">
        <h3 style="font-size: 19px; text-transform: uppercase;margin-left: 10px; margin-right: 10px;text-align:center;">Adjust Arrears</h3>
        <hr style="border-top: 1px solid #ebeff2;margin-bottom: 10px;margin-top: 10px;width: 76%;">
        <form class="" action="{{route('advanced.report.feefixing')}}" method="get">
          @csrf
          <div class="row search-cont">

            <div class="row">
              <div class="col-md-3">
                <label for="" style="margin-left: 10px;">Bill year</label>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <input type="text" class="form-control" disabled name="" id="year" value="<?php echo date('Y'); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label for="" style="margin-left: 10px;">Account No</label>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <input type="text" class="form-control" required name="" id="account_no" v-model="account_no" @keyup="filteredList()" value="">
                </div>

                <small style="color: red;position: relative;top: -11px;display:none;" id="error">Account not found</small>
              </div>
              <!-- <div class="col-md-3">
                <button type="button" onclick="ajaxBillInfo();" class="btn btn-xs btn-info a-dd">find bill</button>
              </div> -->
            </div>

            <div class="row">
              <div class="col-md-12" style="box-shadow: 1px 1px 8px #ccc;padding: 15px; width:50%;width: 98%;background: #faebd7;margin: auto;max-height: 120px;overflow: auto;" v-if="showFilter">
                <div class="data-table card filter-table" v-if="showFilter">
                  <table>
                    <tbody>
                      <template v-for="data in filterList" v-if="account_no.length > 3">
                        <tr>
                          <td class="label-cell">
                            <a href="#" @click.prevent="updateSearchField(data.property_no)"><span style="color:red;" v-text="data.property_no"></span>&nbsp; - @{{data.owner.name}}</a>
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <label for="" style="margin-left: 10px;">Current Arrears</label>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <input type="text" disabled="disable" id="current_arrears" class="form-control" name="" value="">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label for="" required style="margin-left: 10px;">Adjustable Value</label>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <input type="text" class="form-control" name="" value="">
                </div>
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

<script type="text/javascript">
    var app = new Vue({
      el: '#heinz',
      data: {
        f_message: 'Full Payment',
        pno: '',
        properties: [],
        bill: [],
        owner: [],
        collectors: [],
        collector_id: '',
        cashiers: [],
        date: new Date().toJSON().slice(0,10),
        gcrs: [],
        filterList: '',
        account_no: '',
        showFilter: false
      },
      methods: {
          getPBills(query){
            axios.get(`/api/v1/console/get_account_bills/${query}`)
                 .then(response => {this.popDataSet(response)})
                 .catch(error => console.error(error));
          },
          getCStocks(query){
            axios.get(`/api/v1/console/get_collectors_stock/${query}`)
                 .then(response => {console.log(response.data), this.gcrs = response.data.data})
                 .catch(error => console.error(error));
          },
          filteredList () {
            if(this.account_no.length > 4){
              this.showFilter = true
              axios.get(`/api/v1/console/filter_bill_by_ac/${this.account_no.toUpperCase()}/p`)
              .then(response => {this.filterList = response.data.data})
              .catch(error => {console.error(error)});
            }

          },
          updateSearchField (req) {
            this.account_no = req
            this.showFilter = false
          },
          popDataSet(response) {

          }
      },
      created(){

      }


    });
</script>

<script type="text/javascript">

    function ajaxBillInfo() {
      let bill = document.getElementById('account_no').value
      axios.get(`/api/v1/console/get_account_bills/${bill}`)
            .then(response => relateLink(response.data.data))
            .catch(error => console.error(error));
    }

    function relateLink(data){
      if(data == 'false') {
        document.getElementById('error').style.display = "block";
        document.getElementById('account_no').value = ""
        return false
      }
      document.getElementById('error').style.display = "none";
      document.getElementById('current_arrears').value = parseFloat(data.arrears).toFixed(2)
      document.getElementById('year').value = data.year

    }

</script>

@endsection
