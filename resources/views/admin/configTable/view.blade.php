@extends('theme.default')
@section('content')
    <div class="row">
        <div class="col-lg-12 well">
            <div class="col-md-6">
                <label>Config Tables</label>
                <div class="btn-group flex-wrap" role="group" aria-label="Config Tables"
                    style="display: flex; gap: 8px;">
                    <a class="btn btn-outline-primary" href="{{ route('clients.index') }}">Clients</a>
                    @foreach($tables as $key => $table)
                        <button type="button"
                            class="btn {{ isset($tableName) && $tableName == $key ? 'btn-primary' : 'btn-outline-primary' }}"
                            onclick="viewTable('{{ base64_encode($key) }}')">
                            {{ $table['model'] }}
                        </button>

                    @endforeach


                </div>
            </div>

            @if($tableName != '')
                <div class="col-md-4" style="float:right;justify-content:end;align-items:end;">
                    <div class="form-group" style="float:right;align-items:end;">
                        <br>
                        <span class="btn btn-primary" onclick="addRow()"><i class="fa fa-plus-square" aria-hidden="true"></i>
                            New Record</span>
                    </div>
                </div>
            @endif
            
        </div>

        <div class="col-lg-12 well">
            @if(isset($tableRows))
                <table class=" well table table-striped table-bordered table-hover table-condensed " id="myTable">
                    <thead>
                        <tr>
                            @foreach($tables[$tableName]['columns'] as $key => $column)
                                <th>{{strtoupper($column)}}</th>
                            @endforeach
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tableRows as $rowKey => $row)
                            <tr>
                                @foreach($tables[$tableName]['columns'] as $colKey => $value)
                                    <td class="{{$row->id}}">
                                        <input class="{{$row->id}} form-control"
                                            type="{{ in_array($value, ['endDate', 'startDate']) ? 'date' : 'text' }}"
                                            id="{{$row->id}}_{{$value}}" name="{{$value}}" readonly value="{{$row[$value]}}">
                                    </td>
                                @endforeach
                                <td class="{{$row->id}}">
                                    <i id="{{$row->id}}_edit" class="fa fa-edit" style="font-size:20px;color:blue"
                                        onclick="editRow('{{$row->id}}')"></i>&nbsp;
                                    <i id="{{$row->id}}_delete" class="fa fa-trash" style="font-size:20px;color:red"
                                        onclick="confirmDelete('{{$row->id}}','{{$tableName}}')"></i>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($tables[$tableName]['columns']) + 1 }}" class="text-center text-muted">
                                    No records found in this table.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            @else
                List of Config Tables
                <table class=" well table table-striped table-bordered table-hover table-condensed ">
                    <thead>
                        <tr>
                            <th>Table Name</th>
                            <th>Model Name</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($tables as $key => $row)
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$row['model']}}</td>
                            </tr>

                        @endforeach

                    </tbody>
                </table>
            @endif
        </div>
        <style>
            .btn-group .btn {
                padding: 8px 16px;
                border-radius: 5px;
                font-size: 14px;
                transition: all 0.2s ease;
            }

            .btn-group .btn:hover {
                background-color: #0056b3;
                color: #fff;
            }

            .btn-primary {
                background-color: #007bff;
                color: white;
                border: none;
            }

            .btn-outline-primary {
                background-color: white;
                color: #007bff;
                border: 1px solid #007bff;
            }
        </style>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDelete(rowId, tableName) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        postRow(rowId, tableName, 'delete');
                    }
                });
            }
        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                // $('[data-toggle="tooltip"]').tooltip();  
                // viewTable();
            });
            // $('.clicker').click(function(){

            //   $(this).nextUntil('.clicker').slideToggle('normal');
            // });



            function postRow(rowClass, table, whatDo) {
                var tableName = table;
                var url;
                var inputs = $("." + rowClass);
                var valArray = [];
                var fieldArray = [];
                for (var i = 0; i < inputs.length; i++) {
                    valArray[i] = $(inputs[i]).val();
                    fieldArray[i] = $(inputs[i]).attr('name');
                }
                if (whatDo == 'create') {
                    url = '{{ route("admin.configTable.store") }}';
                }
                if (whatDo == 'update') {
                    url = '{{ route("admin.configTable.update") }}';
                }
                if (whatDo == 'delete') {
                    url = '{{ route("admin.configTable.destroy") }}';
                }

                console.log(valArray);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: url,
                    data: { table: tableName, fields: fieldArray, data: valArray },
                }).done(function (data) {
                    console.log(data);
                    if (data.status == true) {
                        location.reload(true);
                    }
                    else {
                        alert('Try Again!');
                        location.reload(true);
                    }
                }).fail(function(xhr, status, error) {
                 if (xhr.status === 419) {
                    alert('CSRF token mismatch');
                    location.reload(true);
                } else {
                    console.error("Error: " + error);
                    alert('An error occurred. Please try again later.');
                }
            });

            }

            function viewTable(encodedTable) {
                if (encodedTable) {
                    window.location.href = "{{URL::to('/Admin/ConfigTable')}}/" + encodedTable + "/show";
                } else {
                    window.location.href = "{{URL::to('/Admin/ConfigTable')}}";
                }
            }
            function addRow() {
                let newId = Date.now(); // unique id based on timestamp
                document.getElementById("myTable").insertRow(1).innerHTML = `
                                                        @if($tableName != '')
                                                            <tr id="postRow">
                                                              @foreach($tables[$tableName]['columns'] as $colKey => $value)
                                                                <td>
                                                                  <input class="postRow form-control" 
                                                                         type="{{ in_array($value, ['endDate', 'startDate']) ? 'date' : 'text' }}"
                                                                         id="${newId}_{{$value}}" 
                                                                         name="{{$value}}" 
                                                                         value="" 
                                                                         {{ $value == 'id' ? 'readonly placeholder="Auto Increment"' : '' }}>
                                                                </td>
                                                              @endforeach
                                                              <td>
                                                                <i class="fa fa-check-square-o" style="color:green;font-size:20px;" 
                                                                   onclick="postRow('postRow','{{$tableName}}','create')"></i>
                                                              </td>
                                                            </tr>
                                                        @endif`;
            }


            function editRow(id) {
                $("." + id).removeAttr('readonly');
                $("#" + id + "_id").attr('readonly', true);
                $("#" + id + "_delete").attr('style', 'color:green;font-size:20px;display:none');
                $("#" + id + "_edit").attr('class', 'fa fa-check-square-o');
                $("#" + id + "_edit").attr('style', 'color:green;font-size:20px;');
                $("#" + id + "_edit").attr('onclick', 'postRow(' + id + ',"<?=$tableName?>","update")');

                $("#" + id + "_delete").attr('class', 'fa fa-close');
                $("#" + id + "_delete").attr('style', 'color:red;font-size:20px;');
                $("#" + id + "_delete").attr('onclick', 'window.location.reload()');

            }

        </script>
@endsection