@extends('layouts.index')
@section('listtest')
    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#createNewSubject" style="margin-bottom: 10px">Tạo môn mới</button>
    <div id="createNewSubject" class="collapse" style="margin-bottom: 5px">
        <form method="post" action="{{url('/admin/manageQuestion')}}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="newSubject">Tên môn học:</label>
                <input type="text" class="form-control" id="newSubject" name="newSubject">
            </div>
            <button type="submit" class="btn btn-success">Tạo</button>
        </form>
    </div>

    @if (isset($subjects))
        @foreach( $subjects as $subject)
            <a class="subject-item btn btn-block btn-default" href="manageQuestion/{{$subject->id}}" style="text-align: left;">
                {{$subject->name}}
            </a>
        @endforeach
    @endif
@endsection