@extends('layouts.index')
@section('listtest')
    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#createNewSubject" style="margin-bottom: 10px">Tạo dạng đề mới</button>
    <div id="createNewSubject" class="collapse" style="margin-bottom: 5px">
        <form method="post" action="{{url('/admin/manageQuestion/'. $subject_id )}}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="newname">Tên dạng đề/chương:</label>
                <input type="text" class="form-control" id="newname" name="newname">
            </div>
            <button type="submit" class="btn btn-success">Tạo</button>
        </form>
    </div>
    @if(isset($units))
        @foreach( $units as $unit)
            <a class="subject-item btn btn-block btn-default" href="{{$subject_id.'/'.$unit->id}}" style="text-align: left;">
                {{$unit->name}}
            </a>
        @endforeach
    @endif
@endsection