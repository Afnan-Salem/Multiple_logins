@if(count($roles)==1)
    <script type="text/javascript">
        window.location = "{{url('home/'.$roles[0]->name.'') }}";
    </script>
@else
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align: center;"><h2>Continue As</h2></div>
                    <div class="panel-body" style="display: table; margin: auto;">
                        @if($roles==null)
                            @include('welcome');
                        @else
                        @foreach ($roles as $role)
                            @if($role->name=='orchestra')
                                <a href="{{ url('/home/orchestra') }}">
                                    <figure style="float:left; padding:10px; text-align:center;">
                                        <img src="<?php echo url('img/orchestra.jpg')?>" alt="HTML5 Icon" style="width:180px;height:180px;">
                                        <figcaption>Orchestra Officer</figcaption>
                                    </figure>
                                </a>
                            @elseif($role->name=='musician')
                                <a href="{{ url('/home/musician') }}">
                                    <figure style="float:left; padding:10px; text-align:center;">
                                        <img src="<?php echo url('img/musician.jpg')?>" alt="HTML5 Icon" style="width:180px; height:180px; ">
                                        <figcaption>Musician</figcaption>
                                    </figure>
                                </a>
                            @else
                                <a href="{{ url('/home/member') }}">
                                    <figure style="float:left; padding:10px; text-align:center;">
                                        <img src="<?php echo url('img/member.jpg')?>" alt="HTML5 Icon" style="width:180px; height:180px; ">
                                        <figcaption>Member</figcaption>
                                    </figure>
                                </a>
                            @endif
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

