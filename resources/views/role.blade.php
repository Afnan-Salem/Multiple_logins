@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    You are logged in as a <?php echo $role."!";?>
                    <!-- set session to keep user role..forget session upon logout in logout.php-->
                    {{Session::set('curr_role', $role)}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
