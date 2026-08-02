<div>
    <div class="card card-shadowless card-custom gutter-b mb-2">
        <div class="card-body">
            <div class="card modal-card card-shadowless card-custom gutter-b mb-2">
{{--                <div class="card-header">--}}
{{--                    <div class="card-title">--}}
{{--                        <h3 class="card-label">{{__('dashboard.contact_details')}}</h3>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="card-body">
                    <div class="text-center">
                        <form action="{{route('dashboard.carTaskAction')}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" name="id" value="{{$carRequest->id}}">
                            <div class="form-group">
                                <textarea class="form-control form-control-solid" rows="7" name="notes" placeholder="{{__('dashboard.remarks')}}">{{$carRequest->notes}}</textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-outline-success ml-2 mb-4" name="status"  value='{{__('dashboard.approved')}}'>
                                <input type="submit" class="btn btn-outline-danger ml-2 mb-4" name="status"  value='{{__('dashboard.rejected')}}'>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
