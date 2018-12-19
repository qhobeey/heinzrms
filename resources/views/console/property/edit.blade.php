@extends('layouts.backend.heinz')

@section('content')
<div class="content">
    <div class="container">

        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal heiz-dashboard-forms" role="form" action="{{route('property.update', $property->property_no)}}" id="addTable" method="post" enctype="multipart/form-data" data-parsley-validate="" novalidate="">
                            @csrf
                            @method('PUT')

                            <h4 class="form-desc">Property Information</h4>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Property No</label>
                                        <input type="text" disabled="true" value="{{$property->property_no}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Building Permit No</label>
                                        <input type="text" value="{{$property->building_permit_no}}" name="building_permit_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Division</label>
                                        <input type="text" value="{{$property->division}}" name="division" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Permit Issue Date</label>
                                        <input type="date" value="{{$property->permit_issue_date}}" name="permit_issue_date" id="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Serial No</label>
                                        <input type="text" value="{{$property->serial_no}}" name="serial_no" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Property Type</label>
                                        <select class="form-control" name="property_type" id="property_type" @blur="getFilteredCategories()">
                                          <option selected="true" disabled value="{{$property->property_type}}"><?= $property->type ? $property->type->description : $property->property_type ?></option>
                                            <template v-for="data in types">
                                                <option :value="data.code">@{{data.description}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Property Category</label>
                                        <select class="form-control" name="property_category" id="">
                                            <option selected="true" disabled value="{{$property->property_category}}"><?= $property->category ? $property->category->description : $property->property_category ?></option>
                                            <template v-for="data in categories">
                                                <option :value="data.code">@{{data.description}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Use Code</label>
                                        <input type="text" value="{{$property->use_code}}" name="use_code" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Occupancy</label>
                                        <input type="text" name="occupancy" value="{{$property->occupancy}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Property Owner</label>
                                        <select class="form-control" name="property_owner" id="">
                                            <option disabled selected="true" value="{{$property->property_owner}}"><?= $property->owner ? $property->owner->name : $property->property_owner ?></option>
                                            <template v-for="data in owners">
                                                <option :value="data.owner_id">@{{data.name}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button class="btn btn-owner form-control">Add new Owner</button>
                                    </div>
                                </div>
                            </div>

                            <h4 class="form-desc">Property Location</h4>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Sub Metro</label>
                                        <select class="form-control" name="zonal_id" id="zonal_id" @blur="getFilteredTas()">
                                            <option disabled selected="true" value="{{$property->zonal_id}}"><?= $property->zonal ? $property->zonal->description : $property->zonal_id ?></option>
                                            <template v-for="data in zonals">
                                                <option :value="data.code">@{{data.description}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Town Area Council</label>
                                        <select class="form-control" name="tas_id" id="tas_id" @blur="getFilteredElectorals()">
                                          <option disabled selected="true" value="{{$property->tas_id}}"><?= $property->tas ? $property->tas->description : $property->tas_id ?></option>
                                            <template v-for="data in tas">
                                                <option :value="data.code">@{{data.description}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Electoral Area</label>
                                        <select class="form-control" name="electoral_id" id="electoral_id" @blur="getFilteredCommunities()">
                                          <option disabled selected="true" value="{{$property->electoral_id}}"><?= $property->electoral ? $property->electoral->description : $property->electoral_id ?></option>
                                            <template v-for="data in electorals">
                                                <option :value="data.code">@{{data.description}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Community</label>
                                        <select class="form-control" name="community_id" id="community_id" @blur="getFilteredStreets()">
                                          <option disabled selected="true" value="{{$property->community_id}}"><?= $property->community ? $property->community->description : $property->community_id ?></option>
                                            <template v-for="data in communities">
                                                <option :value="data.code">@{{data.description}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Street</label>
                                        <select class="form-control" name="street_id" id="">
                                          <option disabled selected="true" value="{{$property->street_id}}"><?= $property->street ? $property->street->description : $property->street_id ?></option>
                                            <template v-for="data in streets">
                                                <option :value="data.code">@{{data.description}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Unit</label>
                                        <select class="form-control" name="unit_id" id="">
                                          <option disabled selected="true" value="{{$property->unit_id}}"><?= $property->unit ? $property->unit->description : $property->unit_id ?></option>
                                            <template v-for="data in units">
                                                <option :value="data.code">@{{data.description}}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Valuation List No</label>
                                        <input type="text" value="{{$property->valuation_no}}" name="valuation_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Block No</label>
                                        <input type="text" name="block_no" value="{{$property->block_no}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">House No</label>
                                        <input type="text" name="house_no" value="{{$property->house_no}}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <h4 class="form-desc">Financial</h4>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Rateable Value</label>
                                        <input type="text" name="rateable_value" value="{{$property->rateable_value}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Assessment No</label>
                                        <input type="text" name="assessment_no" value="{{$property->assessment_no}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Assessment Date</label>
                                        <input type="date" name="assessment_date" value="{{$property->assessment_date}}" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <button type="submit" class="form-control btn btn-danger">Update Entry</button>
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
          pt: '',
          categories: [],
          types: [],
          zonals: [],
          electorals: [],
          communities: [],
          tas: [],
          streets: [],
          units: [],
          owners: [],
          property_type_name: '',
          property_cat_name: '',
          property_owner_name:'',
          property_zonal: ''
      },
      methods: {
        getFilteredCategories () {
          var pid = document.querySelector("#property_type").value
          console.log(pid);
          axios.get(`/api/v1/console/get_categories_property/${pid}`)
              .then(response => {console.table(response.data.props), this.categories = response.data.props})
              .catch(error => console.error(error));
        },
        getFilteredTas () {
          var pid = document.querySelector("#zonal_id").value
          console.log(pid);
          axios.get(`/api/v1/console/get_tas_location/${pid}`)
              .then(response => {console.table(response.data.props), this.tas = response.data.props})
              .catch(error => console.error(error));
        },
        getFilteredElectorals () {
          var pid = document.querySelector("#tas_id").value
          console.log(pid);
          axios.get(`/api/v1/console/get_electorals_location/${pid}`)
              .then(response => {console.table(response.data.props), this.electorals = response.data.props})
              .catch(error => console.error(error));
        },
        getFilteredCommunities () {
          var pid = document.querySelector("#electoral_id").value
          console.log(pid);
          axios.get(`/api/v1/console/get_communities_location/${pid}`)
              .then(response => {console.table(response.data.props), this.communities = response.data.props})
              .catch(error => console.error(error));
        },
      },
      created() {
        var p_type_name = {!! json_encode($property->property_type) !!}
        var p_cat_name = {!! json_encode($property->property_category) !!}
        var p_owner_name = {!! json_encode($property->property_owner) !!}
        var p_zonal = {!! json_encode($property->zonal_id) !!}


        axios.get('/api/v1/console/get_property_categories/')
            .then(response => this.categories = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_property_types/')
            .then(response => this.types = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_property_owners/')
            .then(response => this.owners = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_electorals/')
            .then(response => this.electorals = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_zonals/')
            .then(response => this.zonals = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_communities/')
            .then(response => this.communities = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_tas/')
            .then(response => this.tas = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_streets/')
            .then(response => this.streets = response.data.data)
            .catch(error => console.error(error));
        axios.get('/api/v1/console/get_units/')
            .then(response => this.units = response.data.data)
            .catch(error => console.error(error));
        // axios.get(`/api/v1/console/get_property_type_name/${p_type_name}`)
        //     .then(response => this.property_type_name = response.data.data)
        //     .catch(error => console.error(error));
        // axios.get(`/api/v1/console/get_property_cat_name/${p_cat_name}`)
        //     .then(response => this.property_cat_name = response.data.data)
        //     .catch(error => console.error(error));
        // axios.get(`/api/v1/console/get_property_owner_name/${p_owner_name}`)
        //     .then(response => this.property_owner_name = response.data.data)
        //     .catch(error => console.error(error));
        // axios.get(`/api/v1/console/get_property_zonal/${p_zonal}`)
        //     .then(response => this.property_zonal = response.data.data)
        //     .catch(error => console.error(error));
      }


    });
</script>
@endsection
