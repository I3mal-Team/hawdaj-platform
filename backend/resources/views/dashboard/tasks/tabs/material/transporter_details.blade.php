<div>
{{--    <h3 class="text-muted text-center">--}}
{{--        {{ __("dashboard.".$materialRequest->type) }}--}}
{{--    </h3>--}}
    <div class="card card-shadowless card-custom gutter-b mb-2">
       <div class="card-body">
           <div class="card modal-card card-shadowless card-custom gutter-b mb-2">
               <div class="card-header">
                   <div class="card-title">
                       <h3 class="card-label">{{__('dashboard.transporter_details')}}</h3>
                   </div>
               </div>
               <div class="card-body">
                   <div class="row ">
                       <div class="col-6">
                           <div class="row">
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.company')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->company}}</p>
                               </div>
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.id_type')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->id_type}}</p>
                               </div>
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.phone')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->phone}}</p>
                               </div>
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.vehicle_deal')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->vehicle_details}}</p>
                               </div>
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.remarks')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->remarks}}</p>
                               </div>
                           </div>
                       </div>
                       <div class="col-6">
                           <div class="row">
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.contact_person_name')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->contact_person}}</p>
                               </div>
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.id_number')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->id_number}}</p>
                               </div>
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.people_count')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->people_count}}</p>
                               </div>
                               <div class="col-6 mb-3">
                                   <b class="text-muted">{{__('dashboard.materials')}}</b>
                               </div>
                               <div class="col-6 mb-3">
                                   <p>{{$materialRequest->transporter->materials}}</p>
                               </div>
                           </div>
                       </div>

                   </div>
               </div>
           </div>
       </div>
    </div>
</div>
