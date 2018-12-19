@extends('layouts.backend.heinz')

@section('content')
<div class="content">
    <div class="container">
        <div class="card-box table-responsive">
            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <form class="" action="{{route('property.prepare.bills')}}" method="post">
                      @csrf
                      <div class="col-sm-6">
                          <div class="dataTables_length" id="datatable-responsive_length">
                              <!-- <button type="submit" style="width: 100%; height: 30px;" class="btn btn-primary btn-xs form-control">Prepare Bills</button> -->
                          </div>
                      </div>
                    </form>
                    <div class="col-sm-6">
                      <form class="" action="{{route('property.filter.column')}}" method="get">
                        @csrf
                        <div id="datatable-responsive_filter" class="dataTables_filter">
                            <div class="row">
                              <div class="col-md-1">
                                <h6>Search By: </h6>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <select class="form-control" name="column">
                                    <option value="property_no">Property Number</option>
                                    <option value="property_type">Property Type</option>
                                    <option value="property_category">Property Category</option>
                                    <option value="property_owner">Property Owner</option>
                                    <option value="zonal_id">Zonal</option>
                                    <option value="electoral_id">Electoral</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <input type="text" name="query" class="form-control" value="" placeholder="enter your search parameters here">
                                </div>
                              </div>
                              <div class="col-md-3">
                                <button type="submit" class="form-control">Search</button>
                              </div>
                            </div>
                        </div>
                      </form>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width: 91px;">Property No</th>
                                    <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Property Type</th>
                                    <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Property Category</th>
                                    <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Property Owner</th>
                                    <th class="" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" aria-label="Table No: activate to sort column ascending" style="width: 229px;">Zonal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($properties as $property)
                                <tr role="row" class="odd">
                                    <td class="sorting_1" tabindex="0"><a href="{{route('property.show', $property->property_no)}}">{{$property->property_no}}</a></td>
                                    <td class="sorting_1" tabindex="0"><a href="{{route('property.show', $property->property_no)}}">
                                      @if($property->type)
                                      {{$property->type->description}}
                                      @else
                                      {{$property->property_type}}
                                      @endif
                                    </a></td>
                                    <td class="sorting_1" tabindex="0"><a href="{{route('property.show', $property->property_no)}}">
                                      @if($property->category)
                                      {{$property->category->description}}
                                      @else
                                      {{$property->property_category}}
                                      @endif
                                    </a></td>
                                    <td class="sorting_1" tabindex="0"><a href="{{route('property.show', $property->property_no)}}">
                                      @if($property->owner)
                                      {{$property->owner->name}}
                                      @else
                                      {{$property->property_owner}}
                                      @endif
                                    </a></td>
                                    <td class="sorting_1" tabindex="0">
                                      <a href="{{route('property.show', $property->property_no)}}">
                                        @if($property->zonal)
                                        {{$property->zonal->description}}
                                        @else
                                        {{$property->zonal_id}}
                                        @endif
                                      </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        {{$properties->links()}}
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
          properties: [],
          name: ''
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

        axios.get('/api/v1/console/get_properties_d/')
            .then(response => this.properties = response.data.data)
            .catch(error => console.error(error));
      }


    });
</script>
@endsection
