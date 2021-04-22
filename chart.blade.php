@extends('layouts.app')

@section('content')

    <section class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb-option">
                        <a href="#">Home</a>
                        <span>BLog</span>
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
                <div class="col-lg-6">
                    <div class="breadcrumb-text">
                        <h3>Giełda węgla</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <section class="contact-section spad">
            <div class="container">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Notowania wartości węgla kamiennego</h2>
                    </div>
                </div>
                <canvas id="myChart" style="background-color: #222222"></canvas>

                <div class="contact-option pt-3">
                    <form action="/chart/transaction" class="comment-form contact-form">
                        @csrf
                        <input type="hidden" name="type" value="black">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">

                                    <div class="col-lg-9">
                                        <input type="text" name="buy" autocomplete="off">
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="submit" class="site-btn mr-3" name="form" value="buy">Kup</button>
                                    </div>

                                </div>

                            </div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <input type="text" name="sell" autocomplete="off">
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="site-btn" name="form" value="sell">Sprzedaj
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-8 offset-2">
                                <h3 style="color: white ">Aktualna cena kupna i sprzedaży węgla kamiennego: <span
                                        id="valueBlack"></span></h3>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </section>

        <section class="about-us-section spad">
            <div class="container">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Notowania wartości węgla brunatnego</h2>
                    </div>
                </div>
                <canvas id="myChart2" style="background-color: #2a2a2a"></canvas>

                <div class="contact-option pt-3">
                    <form action="/chartact" class="comment-form contact-form">
                        <input type="hidden" name="type" value="brown">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">

                                    <div class="col-lg-9">
                                        <input type="text" name="buy" autocomplete="off">
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="submit" class="site-btn mr-3" name="form" value="buy">Kup</button>
                                    </div>

                                </div>

                            </div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <input type="text" name="sell" autocomplete="off">
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="site-btn" name="form" value="sell">Sprzedaj
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 offset-2">
                                <h3 style="color: white ">Aktualna cena kupna i sprzedaży węgla brunatnego: <span
                                        id="valueBrown"></span></h3>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </section>

    </section>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>


    <script>
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {

            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Cena węgla kamiennego',
                    data: [],
                    borderWidth: 2,
                    backgroundColor: "transparent",
                    pointBorderColor: "#3490dc",
                    pointHoverBorderColor: "#3490dc",
                    borderColor: "#3490dc",


                }],
            },
            options: {

                legend: {
                    labels: {
                        fontColor: 'white',
                        fontFamily: "'Alegreya Sans SC', sans-serif",
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'white',
                            fontFamily: "'Alegreya Sans SC', sans-serif",
                        },
                        gridLines: {
                            color: '#444444'
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'white',
                            fontFamily: "'Alegreya Sans SC', sans-serif",
                        },
                        gridLines: {
                            color: '#444444'
                        }
                    }]
                }
            }
        });
        var updateChart = function () {
            $.ajax({
                url: "{{ route('api.chartBlack') }}",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    myChart.data.labels = data.labels;
                    myChart.data.datasets[0].data = data.data;
                    var price = data.speed;
                    document.getElementById("valueBlack").innerHTML = price[0].value;
                    myChart.update();
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }

        updateChart();
        setInterval(() => {
            updateChart();
        }, 30000);
    </script>


    <script>
        var cty = document.getElementById("myChart2");
        var myChart2 = new Chart(cty, {

            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Cena węgla brunatnego',
                    data: [],
                    borderWidth: 2,
                    backgroundColor: "transparent",
                    pointBorderColor: "#3490dc",
                    pointHoverBorderColor: "#3490dc",
                    borderColor: "#3490dc",
                }],
            },
            options: {

                legend: {
                    labels: {
                        // This more specific font property overrides the global property
                        fontColor: 'white',
                        fontFamily: "'Alegreya Sans SC', sans-serif",
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'white',
                            fontFamily: "'Alegreya Sans SC', sans-serif",
                        },
                        gridLines: {
                            color: '#444444'
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'white',
                            fontFamily: "'Alegreya Sans SC', sans-serif",
                        },
                        gridLines: {
                            color: '#444444'
                        }
                    }]
                }
            }
        });

        var updateChart2 = function () {
            $.ajax({
                url: "{{ route('api.chartBrown') }}",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    myChart2.data.labels = data.labels;
                    myChart2.data.datasets[0].data = data.data;
                    var price = data.speed;
                    document.getElementById("valueBrown").innerHTML = price[0].value;
                    myChart2.update();
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }

        updateChart2();
        setInterval(() => {
            updateChart2();
        }, 30000);
    </script>

@endsection

