<style type="text/css">
    .badge-notify {
        background: red;
        position: relative;
        top: -10px;
        left: -9px;
    }
</style>

<?php
use Illuminate\Support\Str;
use Carbon\Carbon;

function time_since($since)
{
    $current = Carbon::now();

    $timeFirst = strtotime($since);
    $timeSecond = strtotime($current);
    $differenceInSeconds = $timeSecond - $timeFirst;

    $chunks = array(
        array(60 * 60 * 24 * 365, 'year'),
        array(60 * 60 * 24 * 30, 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24, 'day'),
        array(60 * 60, 'hr'),
        array(60, 'min'),
        array(1, 'sec')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($differenceInSeconds / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
    return $print;
}
?>

<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="{{ url('/') }}"> Dashboard</a>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-top-links navbar-right">
    <li class="dropdown" id='taskNotify'>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="taskAlertBell">
            <i class="fa fa-bell fa-fw"></i>
            <?=(count(Auth::user()->unreadNotifications) > 0) ? '<span class="badge badge-notify" style="background-color: red">' . count(Auth::user()->unreadNotifications) . '</span>' : ''?>
            <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-tasks">

            {{-- Unread Notifications --}}
            @php $unread = Auth::user()->unreadNotifications; @endphp
            @if($unread->count() > 0)
                <li>
                    <a href="#" style="color: red; font-weight: bold;">New / Unread Notifications</a>
                </li>

                {{-- Optional: Delete All Unread Notifications --}}
                <li>
                    <form action="{{ route('notifications.markAllRead') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE') {{-- Use DELETE to remove all --}}
                        <button type="submit" class="btn btn-link" style="color: red; text-decoration: none;">
                            Delete All
                        </button>
                    </form>
                </li>

                @foreach($unread as $notification)
                    <li>
                        @include('theme.notifications.' . Str::snake(class_basename($notification->type)))

                        {{-- Individual Delete --}}
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </li>
                @endforeach

                <li class="divider"></li>
            @endif

            {{-- Recent (Read) Notifications --}}
            <li>
                <a href="#" style="color: blue; font-weight: bold;">Recent Notifications</a>
            </li>

            @forelse(Auth::user()->readNotifications->take(5) as $notification)
                <li>
                    @include('theme.notifications.' . Str::snake(class_basename($notification->type)))
                </li>
            @empty
                <li>
                    <a href="#" class="text-center">No more notifications!</a>
                </li>
            @endforelse

            <li class="divider"></li>

            {{-- Link to all tasks --}}
            <li>
                @if(Auth::user()->type == 'admin')
                    <a class="text-center" href="{{ url('/Admin/Task') }}">
                @else
                        <a class="text-center" href="{{ url('/Task') }}">
                    @endif
                        <strong>See All Tasks</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
            </li>
        </ul>

        <!-- /.dropdown-tasks -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i> {{ Auth::user()->name }} <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="{{ route('Employee') }}"><i class="fa fa-user fa-fw"></i> Profile</a>
            </li>
            @if(Auth::user()->type == 'admin')
                <li><a href="{{ route('register') }}"><i class="fa fa-user fa-fw"></i>Add Admin</a></li>
            @endif
            <li class="divider"></li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out fa-fw"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
        <!-- /.dropdown-user -->
    </li>
    <!-- /.dropdown -->
</ul>
<!-- /.navbar-top-links -->