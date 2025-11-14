@extends('theme.default')
@section('content')
   <div class="row">
      <div class="col-lg-12">
         <h1 class="page-header">Link Informations</h1>
      </div>
      <!-- /.col-lg-12 -->
   </div>
   <!-- /.row -->
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               Link Add
            </div>
            <div class="panel-body">
               @if ($errors->any())
                  <ul class="alert alert-danger">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               @endif
               {!! Form::model($link, [
      'method' => 'POST',
      'url' => ["/Admin/Link/update"],
      'class' => 'form-horizontal',
      'id' => 'link',
      'files' => true
   ]) !!}

               <input type="hidden" name="Id" value="{{$link->id}}">


               <div class="col-md-12">
                  <div class="col-md-6" style="padding-left:30px">
                     <div class="form-group {{ $errors->has('date') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Date `*`', ['class' => 'control-label']) !!}
                        {!! Form::date('date', old(
      'date',
      $link->date
   ), ['class' => 'form-control date-picker', 'required' => true, 'id' => 'date', 'readonly' => true]) !!}
                        {!! $errors->first('date', '
               <p class="help-block">:message</p>
               ') !!}
                     </div>
                  </div>


                  <div class="col-md-6" style="padding-left:30px">
                     <div class="form-group {{ $errors->has('receiverId') ? 'has-error' : ''}}">
                        {!! Form::label('receiverId', 'Send To `*`', ['class' => 'control-label']) !!}
                        {!! Form::select('receiverId', $employees, null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Choose One", 'id' => 'receiverId']) !!}
                        {!! $errors->first('receiverId', '
               <p class="help-block">:message</p>
               ') !!}
                     </div>
                  </div>

                  <div class="col-md-6" style="padding-left:30px">
                     <div class="form-group {{ $errors->has('link') ? 'has-error' : ''}}">
                        {!! Form::label('link', 'Link `*`', ['class' => 'control-label']) !!}
                        {!! Form::text('link', old(
      'link',
      $link->link
   ), ['class' => 'form-control', 'required' => true]) !!}
                        {!! $errors->first('link', '
             <p class="help-block">:message</p>
             ') !!}
                     </div>
                  </div>
                  <div class="col-md-6" style="padding-left:30px">
                     <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                        {!! Form::label('description', 'Description `*`', ['class' => 'control-label']) !!}
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Give Your Description", 'id' => 'description', 'rows' => 2]) !!}
                        {!! $errors->first('description', '
             <p class="help-block">:message</p>
             ') !!}
                     </div>
                  </div>
               </div>





               <div class="col-md-12">


               </div>


               <div class="col-md-12 col-md-offset-2">
                  <div class="col-md-6 form-group">
                     <div class="col-md-4 form-group">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">Cancel</a>
                     </div>
                  </div>
                  <div class="col-md-6 form-group">
                     {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Submit', ['class' => 'btn btn-primary']) !!}
                  </div>
                  
               </div>
            </div>
         </div>
      </div>

      {!! Form::close() !!}
   </div>


@endsection