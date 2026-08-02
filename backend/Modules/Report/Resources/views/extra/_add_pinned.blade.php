<div class="form-group">
    <div class="checkbox-list">
        @foreach($pinneds as $id => $title)
            <label class="checkbox checkbox-lg">
                <input type="checkbox" class="pinned_ids" name="pinned_ids[]" value="{{$id}}"
                       @if(in_array($id,$chart_pinneds)) checked @endif
                />
                <span></span>
                <h4 style="padding: 7px 0 0 0; font-size: 16px;">{{__("dashboard.".$title)}}</h4>
            </label>
        @endforeach
    </div>
</div>

<input type="hidden" name="chart_id" value="{{$chart_id}}">
