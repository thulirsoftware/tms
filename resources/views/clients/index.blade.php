@extends('theme.default')

@section('content')
    <div class="container-fluid py-4 client-container">

        {{-- Header --}}
        <div class="d-flex  align-items-center mb-4 header-bar">
            <h3 class="title"><i class="fa fa-users"></i> Clients<a href="{{ url('/Admin/ConfigTable') }}"
                    class="btn btn-primary" >Back</a></h3>

            @if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Config Tables'))
                <button class="btn add-client-btn" data-toggle="modal" data-target="#addClientModal">
                    <i class="fa fa-plus"></i> Add Client
                </button>
            @endif
        </div>

        {{-- Client Table --}}
        <div class="client-table-wrapper">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Visible</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->location }}</td>
                            <td>
                                <span class="status-badge {{ $client->status == 'active' ? 'active' : 'inactive' }}">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="visible-badge {{ $client->isvisible == 'yes' ? 'yes' : 'no' }}">
                                    {{ ucfirst($client->isvisible) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('clients.view', $client->id) }}" class="action-btn view-btn" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Config Tables'))
                                    <button class="action-btn delete-btn delete-client" data-id="{{ $client->id }}" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    @if($clients->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">No clients found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Client Modal --}}
    @include('clients.modal.add')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background: linear-gradient(to right, #f0f4f8, #e8ecf3);
            font-family: 'Poppins', sans-serif;
        }

        .client-container {
            animation: fadeIn 0.8s ease-in-out;
        }

        .header-bar {
            background: #fff;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            margin-top: 20px;

        }

        .title {
            font-weight: 600;
            color: #007bff;
            margin: 0;
            letter-spacing: 0.5px;
            
        }
        .title  a{
            margin-left: 20px;
        }

        /* 🌟 Add Client Button */
        .add-client-btn {
            background: #170069cf;
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .add-client-btn:hover {
            transform: translateY(-2px);
            color: white
        }

        /* 📋 Table Styling */
        .client-table-wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: 0.3s ease;
        }

        .client-table-wrapper:hover {
            transform: scale(1.01);
        }

        .table th {
            background: rgba(3, 75, 134, 0.67);
            color: #fff;
            font-weight: 500;
            text-transform: uppercase;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f7ff;
            transform: scale(1.01);
        }

        /* 🟢 Status Badges */
        .status-badge,
        .visible-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            text-transform: capitalize;
        }





        /* ⚙️ Action Buttons */
        .action-btn {
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 5px;
            font-size: 18px;
        }

        .action-btn i {
            transition: 0.3s ease;
        }

        .view-btn:hover i {
            color: #007bff;
            transform: scale(1.2);
        }

        .delete-btn:hover i {
            color: #dc3545;
            transform: scale(1.2);
        }

        /* ✨ Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        $(document).on('click', '.delete-client', function () {
            if (confirm('Delete this client?')) {
                let id = $(this).data('id');
                $.ajax({
                    url: `/clients/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => {
                        $(this).closest('tr').css('background-color', '#ffdddd').fadeOut(600, function () {
                            $(this).remove();
                        });
                    }
                });
            }
        });
    </script>
@endsection