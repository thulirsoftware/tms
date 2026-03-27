<div class="col-md-4">
    <div class="form-group">
        <label>Config Tables</label>
        <select class="form-control" id="tableFilter" onchange="viewTable()">
            <option value="">Select Table Name</option>
            @foreach($tables as $key => $table)
                <option value="{{base64_encode($key)}}" <?=isset($tableName) ? (($tableName == $key) ? 'selected' : '') : ''?>>
                    {{$table['model']}}
                </option>
            @endforeach
        </select>
    </div>
</div>
@if($tableName != '')
    <div class="col-md-4">
        <div class="form-group">
            <br>
            <span class="btn btn-primary" onclick="addRow()"><i class="fa fa-plus-square" aria-hidden="true"></i> New
                Record</span>
        </div>
    </div>
@endif
<div class="col-md-4">
    <br>

</div>
</div>

<div class="col-lg-12 well" id="tableFilters">
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

                    @foreach($tableRows as $rowKey => $row)
                                    <tr>
                                        @foreach($tables[$tableName]['columns'] as $colKey => $value)
                                            <td class="{{$row->id}}"><input class="{{$row->id}} form-control" type="text"
                                                    id="{{$row->id}}_{{$value}}" name="{{$value}}" readonly="" value="{{$row[$value]}}"></td>
                                        @endforeach
                                        <td class="{{$row->id}}">
                                            <i id="{{$row->id}}_edit" class="fa fa-edit" style="font-size:20px;color:blue"
                                                onclick="editRow('<?=$row->id?>')"></i>&nbsp;
                                            <i id="{{$row->id}}_delete" class="fa fa-trash" style="font-size:20px;color:red"
                                                onclick="confirmDelete('<?=$row->id?>')"></i>

                                        </td>
                                    </tr>
                                    <div id="{{$row->id}}" class="modal fade" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Close or Delete Project</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>You Want to Delete or Close the Project</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="DeleteRow('<?=$row->id?>','delete')">Delete</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="DeleteRow('<?=$row->id?>','close')">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </div>

                    @endforeach

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
    <script>
        function confirmDelete(rowId) {
            if (confirm("Are you sure you want to delete this record?")) {
                DeleteRow(rowId, 'delete'); // your existing delete function
            } else {
                console.log("Deletion cancelled for ID:", rowId);
            }
        }
    </script>

@endif
</div>