<div class="card card-custom gutter-b mb-2 card-shadowless">
    <div class="card-body">
       <div class="card card-custom modal-card gutter-b mb-2 card-shadowless">
           <div class="card-header">
               <div class="card-title">
                   <h3 class="card-label">{{__('dashboard.transporter_details')}}</h3>
               </div>
           </div>
           <div class="card-body">
               <div class="row ">
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.company')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->company}}</p>
                   </div>
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.contact_person')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->contact_person}}</p>
                   </div>
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.id_type')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->id_type}}</p>
                   </div>
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.id_number')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->id_number}}</p>
                   </div>
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.phone')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->phone}}</p>
                   </div>
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.people_count')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->people_count}}</p>
                   </div>
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.vehicle_deal')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->vehicle_details}}</p>
                   </div>
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.materials')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->materials}}</p>
                   </div>
                   <div class="col-3 mb-3">
                       <b class="text-muted">{{__('dashboard.remarks')}}</b>
                   </div>
                   <div class="col-3 mb-3">
                       <p>{{$request->transporter->remarks}}</p>
                   </div>
               </div>
           </div>
       </div>
    </div>
</div>
