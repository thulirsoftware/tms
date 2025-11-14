@extends('theme.default')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">List of Links</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <?php 
                                    $senderId = Session::get('UserSenderId');
    $send_date = Session::get('UserSendDate');
    $deleted = Session::get('UserDeleted');
                                  ?>
    @if(Auth::user()->type != 'admin')

        <div class="row">

            <div class="col-md-3">
                <div class="form-group">

                    <label>Sender Name</label>

                    <select id="receiverId" name="senderId" class="form-control">
                        <option value="">--- Select Sender Name ---</option>
                        @foreach ($employees as $employees)

                            <option value="{{ $employees->id }}" {{ $employees->id == $senderId ? 'selected' : ''}}>
                                {{ $employees->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">Deleted Links</label>
                    <select class="form-control" id="deleted">
                        <option value="">Select</option>
                        <option value="Y" {{ "Y" == $deleted ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{ "N" == $deleted ? 'selected' : ''}}>No</option>option>
                    </select>

                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Send Date</label>
                <input type="date" class="form-control" id="send_date" value="{{ $send_date }}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-info btn-rounded" style="
                    margin-top: 36%;" onclick="search()"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
            </div>
            <div class="col-md-1">
                &nbsp;&nbsp;&nbsp;<a class="btn btn-info btn-rounded" style="
                    margin-top: 36%;" href="{{ route('links.reset') }}"><i class="fa fa-refresh" aria-hidden="true"></i></a>
            </div>
            <div class="col-md-1">
                <a class="btn btn-info btn-rounded" href="{{ route('links.add') }}" style="
                    margin-top: 36%;"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
            </div>

        </div>
    @endif
    <div style="float: right;">

    </div><br><br>
    <div id="linksFilteration">
        @include('links.components.getData')
    </div>
    <script type="text/javascript">
        var elems = document.getElementsByClassName('Linkconfirmation');
        var confirmIt = function (e) {
            if (!confirm('Are you sure you want to delete link?')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }
    </script>

    <script>
        function search() {
            var senderId = document.getElementById('receiverId').value;
            var deleted = document.getElementById('deleted').value;
            var send_date = document.getElementById('send_date').value;

            $.ajax({
                type: 'get',
                url: '{{route('links.search')}}',
                data: { 'senderId': senderId, 'send_date': send_date, 'deleted': deleted },
                success: function (data) {
                    console.log(data, "data");
                    $('#linksFilteration').empty();
                    $('#linksFilteration').html(data['links']);
                }
            });


        }

    </script>
@endsection