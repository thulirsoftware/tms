<table class="table table-striped table-bordered table-hover" id="example">
    <?php
$i = 1;
        ?>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Sender Name</th>
            <th>Receiver Name</th>
            <th>Date</th>
            <th>Description</th>
            <th>Link</th>
        </tr>
    </thead>
    <tbody>


        @foreach($links as $link)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $link->sender?->name }}</td>
                <td>{{ $link->receiver?->name }}</td>
                <td>{{ $link->date }}</td>
                <td>{{ $link->description }}</td>
                <td><a href="{{ $link->link }}" target="_blank">Open</a></td>
                <td>
                    <a href="{{ url('/Link/' . $link->id . '/edit') }}">
                        <i class="fa fa-edit" style="font-size:20px;color:blue"></i>
                    </a>&nbsp;
                    <a class="Linkconfirmation" href="{{ url('/Link/' . $link->id . '/delete') }}">
                        <i class="fa fa-trash" style="font-size:20px;color:red"></i>
                    </a>
                </td>
            </tr>
        @endforeach



    </tbody>
</table>