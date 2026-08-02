
<div class="card card-custom gutter-b mb-2 card-shadowless">
    <div class="card-body">
        <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
{{--            <div class="card-header">--}}
{{--                <div class="card-title">--}}
{{--                    <h3 class="card-label">{{__('dashboard.contact_details')}}</h3>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="card-body">
                <div class="row  text-center">
                    <div class="col-md-12">
                        <form action="{{route('dashboard.taskAction')}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" name="id" value="{{$task->id}}">
                            <div class="form-group">
                                <textarea class="form-control form-control-solid" rows="7" name="notes" placeholder="{{__('dashboard.remarks')}}">{{$task->notes}}</textarea>
                            </div>
                            @if($task->status === 'confirmed')
                                <div class="d-flex justify-content-end">
                                    <input type="submit" class="btn btn-outline-success ml-2 mb-4" name="status"  value='Approve'>
                                    <input type="submit" class="btn btn-outline-danger ml-2 mb-4" name="status"  value='Reject'>
                                    <input type="submit" class="btn btn-outline-secondary ml-2 mb-4" name="status"  value='Monitor'>
                                </div>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

