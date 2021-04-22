@extends('layouts.app')

@section('content')

    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/vendor/perfect-scrollbar/perfect-scrollbar.css">

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb-option">
                        @if($errors->any())
                            @foreach($errors->all() as $error)

                                <div class="alert alert-primary msg" id="error">
                                    {{$error}}
                                </div>
                            @endforeach
                        @endif
                        @if (session('error'))
                            <div class="alert alert-primary msg" id="error">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 text-center">
                    <div class="breadcrumb-text">
                        <h3>Rynek Pracowników</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-us-section spad">
        <div class="container">
            <div class="row-12">
                <div class="pl-2 pr-3 pb-3" >
                    <label for="MineDes">
                        <h5 style="color: white">Zatrudnienie górnika spowoduje przeniesienie go do twoich pracowników. Po zatrudnieniu nie będziesz mógł zwolinić go przez tydzień.</h5>

                    </label>
                </div>
                <div class="table100 ver3 m-b-110">
                    <div class="table100-head">
                        <table>
                            <thead>
                            <tr class="row100 head">
                                <th class="cell100 column1"></th>
                                <th class="cell100 column2" style="">Pracownik</th>
                                <th class="cell100 column3">Pensja</th>
                                <th class="cell100 column3">Wiek</th>
                                <th class="cell100 column3">Doświadczenie</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="table100-body js-pscroll">
                        <table>
                            <tbody>

                            @foreach($workers as $row)
                                <tr class="row100 body">
                                    <td class="cell100 column2" width='20%'><a class="btn btn-primary ml-4" href="{{action('WorkerController@hire',$row['id'])}}">Zatrudnij</a></td>
                                    <td class="cell100 column2" width='20%'>{{$row['name']}}</td>
                                    <td class="cell100 column3" width='20%'>{{$row['salary']}}</td>
                                    <td class="cell100 column3" width='20%'>{{$row['age']}}</td>
                                    <td class="cell100 column3" width='20%'>{{$row['experience']}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>


                    </div>
                </div>
                </div>
            </div>
    </section>


    <section class="member-section spad ap-member">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Zatrudnieni górnicy</h2>
                    </div>
                </div>
            </div>
            <div class="pl-2 pr-3 pb-3" >
                <label for="MineDes">
                    <h5 style="color: white">Przypisanie górnika spowoduje że zacznie on wydobywać surowiec w wybranej kopalni (+25% jeśli będzie ona zgodna z jego doświadczeniem).</h5>
                </label>
            </div>
            <div class="pl-2 pr-3 pb-3" >
                <label for="MineDes">
                    <h5 style="color: white">Wypowiedzenie umowy górnikowi spowoduje że nie można go przypisać do kopalni ale jego płaca zmiensza się o 66% do czasu jego zwolnienia.</h5>
                </label>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-6">
                    <div class="table100 ver3 m-b-110">
                        <div class="table100-head">
                            <table>
                                <thead>
                                <tr class="row100 head">
                                    <th class="cell100 column4"></th>
                                    <th class="cell100 column5">Pracownik</th>
                                    <th class="cell100 column5">Pensja</th>
                                    <th class="cell100 column5">Wiek</th>
                                    <th class="cell100 column5">Doświadczenie</th>
                                    <th class="cell100 column5"></th>

                                </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="table100-body js-pscroll">
                            <table>
                                <tbody>

                                @foreach($user_workers as $uw)
                                    <tr class="row100 body">
                                        <td class="cell100 column4" width='25%'><!-- Design tiré du site flatuicolors.com -->
                                            <!-- Bouton Select de base -->
                                            @if($uw['denunciation']!=1)
                                            <form method="POST" action="/workers/add"  enctype="multipart/form-data">
                                                @csrf
                                                <label>
                                                        <select name="mine" class="form-control" style=" outline: 0; border-width: 0 0 2px;  box-shadow: none !important; border-color: #808080; color: #808080; background-color: #222;">
                                                            @if($uw['mine_id']==null)
                                                                <option value="" selected>Brak przydziału</option>
                                                            @endif
                                                                @foreach($mine as $m)
                                                            {{$ty=$uw['mine_id']}}

                                                                @if($ty==$m['id'])
                                                                <option value="" selected>{{$m['name']}}</option>
                                                                        <option value="" >Brak przydziału</option>
                                                                    @elseif($m->worker->count()<$m->workers)
                                                                <option value="{{$m['id']}}" >{{$m['name']}}</option>
                                                            @endif
                                                                @endforeach
                                                        </select>
                                                </label>
                                                <input type="hidden" name="worker" value="{{$uw['id']}}" />
                                                <button type="submit" class="btn btn-primary ml-4" name="form1">Przydziel</button>
                                            </form>
                                            @endif

                                        </td>
                                        <td class="cell100 column5" width='15%'>{{$uw['name']}}</td>
                                        <td class="cell100 column5" width='15%'>{{$uw['salary']}}</td>
                                        <td class="cell100 column5" width='15%'>{{$uw['age']}}</td>
                                        <td class="cell100 column5" width='15%'>{{$uw['experience']}}</td>

                                            <td class="cell100 column5" width='15%'>
                                                <form method="POST" action="/workers/fire"  enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="worker" value="{{$uw['id']}}">

                                                @if($uw['agreement']<$date)
                                                        @csrf
                                                        <button type="submit" class="btn btn-dark ml-4" name="form" value="zwolnij">Zwolnij</button>
                                                @endif
                                                    <button type="submit" class="btn btn-primary ml-4" name="form" value="wypowiedz">Wypowiedz</button>
                                                    @if($uw['denunciation']!=1)
                                                    <button type="submit" class="btn btn-dark ml-4" name="form" value="sprzedaj">Odstąp pracownika</button>
                                                @endif
                                                </form>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load("current", {packages:["corechart"]});
                    google.charts.setOnLoadCallback(drawChart);

                    var age = {!! json_encode($user_age) !!}
                    var salary = {!! json_encode($user_salary) !!}
                    var correct = {!! json_encode($correct) !!}
                    var correctt = Number(correct.wkok) + Number(correct.wbok) + Number(correct.eok);

                    function drawChart() {

                        var data = google.visualization.arrayToDataTable([
                            ['Task', 'Hours per Day'],
                            ['20-30', age.age20],
                            ['31-40', age.age30],
                            ['41-50', age.age40]
                        ]);

                        var data2 = google.visualization.arrayToDataTable([
                            ['Task', 'Hours per Day'],
                            ['30-35', salary.salary30],
                            ['36-40', salary.salary35],
                            ['41-45', salary.salary40]
                        ]);

                        var data3 = google.visualization.arrayToDataTable([
                            ['Task', 'Hours per Day'],
                            ['Niepoprawnie', correct.total-correctt],
                            ['Poprawnie', correctt],

                        ]);

                        var options = {
                            title: 'Średnia wieku pracowników :'+age.avg_age,
                            is3D: true,
                            legendTextStyle: { color: '#FFF', fontName:"'Alegreya Sans SC', sans-serif" },
                            titleTextStyle: { color: '#FFF',fontName:"'Alegreya Sans SC', sans-serif" },
                            backgroundColor: { fill:'transparent' },
                            'colors' : ["#004478","#3490dc", "#2a2a2a"],

                        };

                        var options2 = {
                            title: 'Średnia pensji zatrudnionych :'+salary.avg_sal,
                            is3D: true,
                            legendTextStyle: { color: '#FFF', fontName:"'Alegreya Sans SC', sans-serif" },
                            titleTextStyle: { color: '#FFF',fontName:"'Alegreya Sans SC', sans-serif" },
                            backgroundColor: { fill:'transparent' },
                            'colors' : ["#3490dc","#004478", "#2a2a2a"],
                        };

                        var options3 = {
                            title: 'Poprawność wykorzystania doświadczenia :',
                            is3D: true,
                            legendTextStyle: { color: '#FFF', fontName:"'Alegreya Sans SC', sans-serif" },
                            titleTextStyle: { color: '#FFF',fontName:"'Alegreya Sans SC', sans-serif" },
                            backgroundColor: { fill:'transparent' },
                            'colors' : ["#2a2a2a","#004478", "#3490dc"],

                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                        chart.draw(data, options);
                        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d2'));
                        chart.draw(data2, options2);
                        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d3'));
                        chart.draw(data3, options3);
                    }
                </script>
            </div>
        </div>
                <div class="container-fluid">
                    <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="member-item set-bg" data-setbg="img/member/member-4.jpg">
                        <div id="piechart_3d" style="width: 650px; height: 500px; margin: 0 auto;"></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="member-item set-bg" data-setbg="img/member/member-4.jpg">
                        <div id="piechart_3d2" style="width: 650px; height: 500px;"></div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="member-item set-bg" data-setbg="img/member/member-4.jpg">
                        <div id="piechart_3d3" style="width: 650px; height: 500px;"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="about-us-section spad">
        <div class="container">
            <div class="row-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>Twoi odstąpieni pracownicy</h2>
                        </div>
                    </div>
                </div>
                <div class="table100 ver3 m-b-110">
                    <div class="table100-head">
                        <table>
                            <thead>
                            <tr class="row100 head">
                                <th class="cell100 column4"></th>
                                <th class="cell100 column5">Cena</th>
                                <th class="cell100 column5">Gracz</th>
                                <th class="cell100 column5">Pensja</th>
                                <th class="cell100 column5">Wiek</th>
                                <th class="cell100 column5">Doświadczenie</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="table100-body js-pscroll">
                        <table>
                            <tbody>

                            @foreach($workersSell as $sell)
                                @if($sell->user_id==auth()->user()->id)
                                <tr class="row100 body">
                                    <td class="cell100 column4 p-3">Najwyższa oferta:{{$sell->deal}}
                                        @if($sell->confirm==1)
                                        <a class="btn btn-primary" href="{{route('decline',['trade'=>$sell->tid])}}">Odmów</a>
                                            @elseif($sell->deal!=null)
                                            <br>
                                            <a class="btn btn-primary" href="{{route('confirm',['trade'=>$sell->tid])}}">Zatwierdź</a>
                                            <a class="btn btn-danger" href="{{route('delete',['trade'=>$sell->tid])}}">Usuń </a>
                                        @endif
                                    </td>
                                    <td class="cell100 column5">{{$sell->price}}</td>
                                    <td class="cell100 column5">{{$sell->player}}</td>
                                    <td class="cell100 column5">{{$sell->salary}}</td>
                                    <td class="cell100 column5">{{$sell->age}}</td>
                                    <td class="cell100 column5">{{$sell->experience}}</td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>Zakup pracownika</h2>
                        </div>
                    </div>
                </div>
                <div class="table100 ver3 m-b-110">
                    <div class="table100-head">
                        <table>
                            <thead>
                            <tr class="row100 head">
                                <th class="cell100 column4"></th>
                                <th class="cell100 column5">Cena</th>
                                <th class="cell100 column5">Gracz</th>
                                <th class="cell100 column5">Pensja</th>
                                <th class="cell100 column5">Wiek</th>
                                <th class="cell100 column5">Doświadczenie</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="table100-body js-pscroll">
                        <table>
                            <tbody>

                            @foreach($workersSell as $sell)
                                @if($sell->user_id!=auth()->user()->id)
                                    <tr class="row100 body">
                                        <td class="cell100 column4">
                                            <form method="POST" action="/workers/trade"  enctype="multipart/form-data">
                                                @csrf
                                                @if($sell->confirm!=true)
                                                    <a class="btn btn-primary" href="{{route('remindHelper',['price'=>$sell->price,'buyer'=>auth()->user()->id,'worker'=>$sell->wid,'seller'=>$sell->user_id, 'trade'=>$sell->tid])}}">Odkup pracownika</a>
                                                    <input type="text" autocomplete="off" name="price" class="form-control">
                                                <input type="hidden" name='worker' value="{{$sell->wid}}">
                                                <input type="hidden" name='id' value="{{auth()->user()->id}}">
                                                <button type="submit" class="btn btn-primary">Zaproponuj cenę</button>
                                                    @elseif($sell->confirm=true && $sell->dealer_id==auth()->user()->id)
                                                    <a class="btn btn-primary" href="{{route('remindHelper',['price'=>$sell->deal,'buyer'=>auth()->user()->id,'worker'=>$sell->wid,'seller'=>$sell->user_id, 'trade'=>$sell->tid])}}">Odkup pracownika za {{$sell->deal}}</a>
                                                @else
                                                    <a class="btn btn-primary" href="{{route('remindHelper',['price'=>$sell->price,'buyer'=>auth()->user()->id,'worker'=>$sell->wid,'seller'=>$sell->user_id, 'trade'=>$sell->tid])}}">Odkup pracownika</a>
                                                @endif
                                            </form>
                                        </td>
                                        <td class="cell100 column5">{{$sell->price}}</td>
                                        <td class="cell100 column5">{{$sell->player}}</td>
                                        <td class="cell100 column5">{{$sell->salary}}</td>
                                        <td class="cell100 column5">{{$sell->age}}</td>
                                        <td class="cell100 column5">{{$sell->experience}}</td>
                                    </tr>
                                @endif
                            @endforeach

                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Member Section End -->

    <!-- Js Plugins -->
{{--    <script src="css/vendor/jquery/jquery-3.2.1.min.js"></script>--}}

    <script src="css/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script>
        $('.js-pscroll').each(function(){
            var ps = new PerfectScrollbar(this);

            $(window).on('resize', function(){
                ps.update();
            })
        });


    </script>



@endsection
