<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                <li class="text-muted menu-title">Navigation</li>

                <li class="has_sub">
                    <a href="{{route('console.dashboard')}}" class="waves-effect"><i class="ti-home"></i> <span> {{__('Dashboard')}} </span> </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i><span> {{__('Value Books')}} </span><span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('stock.create')}}">{{__('New Value Book')}}</a></li>
                        <li><a href="{{route('stock.index')}}">{{__('All Value Books')}}</a></li>
                        <li><a href="{{route('stock.issue')}}">{{__('Issue Value Books')}}</a></li>
                        <li><a href="{{route('stock.find')}}">{{__('Find Value Books')}}</a></li>
                        <li><a href="{{route('stock.return')}}">{{__('Return Value Books')}}</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-layout"></i><span> {{__('Property')}} </span><span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('property.index')}}">{{__('All Properties')}}</a></li>
                        <li><a href="{{route('property.create')}}">{{__('New Property')}}</a></li>
                        <!-- <li><a href="#">{{__('Property Occupants')}}</a></li> -->
                        <li><a href="{{route('property.types')}}">{{__('Property Type')}}</a></li>
                        <li><a href="{{route('property.categories')}}">{{__('Property Category')}}</a></li>
                        <!-- <li><a href="{{route('stock.return')}}">{{__('Fee Fixing')}}</a></li> -->
                        <li><a href="{{route('property.owners')}}">{{__('Property Owners')}}</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-direction-alt"></i><span> {{__('Business')}} </span><span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('business.index')}}">{{__('All Business')}}</a></li>
                        <li><a href="{{route('business.create')}}">{{__('New Business')}}</a></li>
                        <!-- <li><a href="#">{{__('Business Occupants')}}</a></li> -->
                        <li><a href="{{route('business.types')}}">{{__('Business Type')}}</a></li>
                        <li><a href="{{route('business.categories')}}">{{__('Business Category')}}</a></li>
                        <!-- <li><a href="{{route('stock.return')}}">{{__('Fee Fixing')}}</a></li> -->
                        <li><a href="{{route('business.owners')}}">{{__('Business Owners')}}</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-notepad"></i><span> {{__('Records')}} </span><span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                        <!-- <li><a href="{{route('property.records.bills')}}">{{__('Bills')}}</a></li> -->
                        <li><a href="{{route('account.bills')}}">{{__('Bills')}}</a></li>
                        <li><a href="{{route('cashiers.index')}}">{{__('Cashier Payments')}}</a></li>
                        <li><a href="{{route('property.payments.payment')}}">{{__('Individual Payments')}}</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-settings"></i><span> {{__('System Setups')}} </span><span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                      <li class="has_sub">
                          <a href="javascript:void(0);" class="waves-effect"><span> {{__('Location Info')}} </span><span class="menu-arrow"></span></a>
                          <ul class="list-unstyled">
                              <li><a href="{{route('location.zonals')}}">{{__('Zonal Council/Sub Metro')}}</a></li>
                              <li><a href="{{route('location.tas')}}">{{__('Town Area Council')}}</a></li>
                              <li><a href="{{route('location.electorals')}}">{{__('Electoral Area')}}</a></li>
                              <li><a href="{{route('location.communities')}}">{{__('Community')}}</a></li>
                              <!-- <li><a href="{{route('location.constituencies')}}">{{__('Constituency')}}</a></li> -->
                              <li><a href="{{route('location.units')}}">{{__('Unit')}}</a></li>
                              <li><a href="{{route('location.streets')}}">{{__('Streets')}}</a></li>
                          </ul>
                      </li>
                      <li class="has_sub">
                          <a href="{{route('clients')}}" class="waves-effect"> <span> {{__('Clients')}} </span> </a>
                      </li>
                      <li><a href="{{route('setups.sms')}}">{{__('SMS')}}</a></li>
                      <li class="has_sub">
                          <a href="javascript:void(0);" class="waves-effect"><span> {{__('System Personnels')}} </span> <span class="menu-arrow"></span></a>
                          <ul class="list-unstyled">
                              <li><a href="{{route('accountants.create')}}">{{__('Add Accountant')}}</a></li>
                              <li><a href="{{route('accountants.index')}}">{{__('All Accountants')}}</a></li><hr>
                              <li><a href="{{route('supervisors.create')}}">{{__('Add Supervisor')}}</a></li>
                              <li><a href="{{route('supervisors.index')}}">{{__('All Supervisors')}}</a></li><hr>
                              <li><a href="{{route('collectors.create')}}">{{__('Add Collector')}}</a></li>
                              <li><a href="{{route('collectors.index')}}">{{__('All Collectors')}}</a></li>
                              <li><a href="{{route('cashiers.create')}}">{{__('Add Cashier')}}</a></li>
                          </ul>
                      </li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-notepad"></i><span> {{__('Reports')}} </span><span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                      <li><a href="{{route('report.property.account')}}">{{__('Property Report')}}</a></li>
                      <li><a href="{{route('report.business.account')}}">{{__('Business Report')}}</a></li>
                      <li><a href="{{route('report.bills.account')}}">{{__('Bill Report')}}</a></li>
                      <!-- <li class="has_sub">
                          <a href="javascript:void(0);" class="waves-effect"><span> {{__('Properties')}} </span><span class="menu-arrow"></span></a>
                          <ul class="list-unstyled">
                              <li><a href="{{route('report.property.account')}}">{{__('account')}}</a></li>
                              <li><a href="#">{{__('owners')}}</a></li>
                          </ul>
                      </li>
                      <li class="has_sub">
                          <a href="javascript:void(0);" class="waves-effect"><span> {{__('Business')}} </span><span class="menu-arrow"></span></a>
                          <ul class="list-unstyled">
                              <li><a href="{{route('report.business.account')}}">{{__('account')}}</a></li>
                              <li><a href="#">{{__('owners')}}</a></li>
                          </ul>
                      </li>
                      <li class="has_sub">
                          <a href="javascript:void(0);" class="waves-effect"><span> {{__('Billing')}} </span><span class="menu-arrow"></span></a>
                          <ul class="list-unstyled">
                              <li><a href="{{route('report.bills.account')}}">{{__('account')}}</a></li>
                              <li><a href="#">{{__('property')}}</a></li>
                              <li><a href="#">{{__('business')}}</a></li>
                          </ul>
                      </li> -->
                    </ul>
                </li>



            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->
