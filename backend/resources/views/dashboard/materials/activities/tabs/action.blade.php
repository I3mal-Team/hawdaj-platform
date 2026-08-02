<div class="card card-custom gutter-b mb-2 card-shadowless">

    <div class="card-body">
        <form action="{{route('dashboard.materialAction')}}" method="post">
            @csrf
            @method('put')
            <input type="hidden" name="id" value="{{$request->id}}">
            <div class="form-group">
                <textarea class="form-control form-control-solid" rows="7" cols="7" name="status_remarks"
                          placeholder="{{__('dashboard.remarks')}}">{{$request->status_remarks}}</textarea>
            </div>
            <div class="text-right">
                @if ($request->status != 'receive' && $request->status != 'canceled')
                    @if(in_array($request->type ,['inward_non-returnable','personal_request']))
                        <input type="submit" class="btn btn-outline-success ml-2 mb-4" name="status"
                               value='{{__('dashboard.receive')}}'>
                        <input type="submit" class="btn btn-outline-primary ml-2 mb-4" name="status"
                               value='{{__('dashboard.partially_receive')}}'>
                        <input type="submit" class="btn btn-outline-secondary ml-2 mb-4" name="status"
                               value='{{__('dashboard.not_receive')}}'>
                    @elseif($request->type === 'inward_returnable')
                        <input type="submit" class="btn btn-outline-success ml-2 mb-4" name="status"
                               value='{{__('dashboard.return')}}'>
                        <input type="submit" class="btn btn-outline-primary ml-2 mb-4" name="status"
                               value='{{__('dashboard.partially_return')}}'>
                        <input type="submit" class="btn btn-outline-secondary ml-2 mb-4" name="status"
                               value='{{__('dashboard.not_return')}}'>
                    @else
                        <input type="submit" class="btn btn-outline-success ml-2 mb-4" name="status"
                               value='{{__('dashboard.dispatch')}}'>
                        <input type="submit" class="btn btn-outline-primary ml-2 mb-4" name="status"
                               value='{{__('dashboard.partially_dispatch')}}'>
                        <input type="submit" class="btn btn-outline-secondary ml-2 mb-4" name="status"
                               value='{{__('dashboard.not_dispatch')}}'>
                    @endif
                    <input type="submit" class="btn btn-outline-danger ml-2 mb-4" name="status"
                           value='{{__('dashboard.reject')}}'>
                @endif
            </div>
        </form>
    </div>
</div>

