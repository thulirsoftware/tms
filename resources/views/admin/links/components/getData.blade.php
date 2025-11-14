<table class="table table-striped table-bordered table-hover">
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
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($links as $link)
            <?php 
                $sendername = App\User::find($link->senderId);
                $receivername = App\User::find($link->receiverId);
            ?>
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $sendername ? $sendername->name : 'Unknown Sender' }}</td>
                <td>{{ $receivername ? $receivername->name : 'Unknown Receiver' }}</td>
                <td>{{ $link->date }}</td>
                <td>{{ $link->description }}</td>
                <td><a href="{{ $link->link }}" target="_blank">Open</a></td>
                <td>
                    <a href="{{ url('/Admin/Link/' . $link->id . '/edit') }}">
                        <i class="fa fa-edit" style="font-size:20px;color:blue"></i>
                    </a>&nbsp; 
                    <a class="Linkconfirmation" href="{{ url('/Link/' . $link->id . '/delete') }}" style="font-size:20px;color:red">
                        <i class="fa fa-trash" style="font-size:20px;color:red"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
