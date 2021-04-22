@extends('layouts.app')

@section('content')

    <section class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb-option">
                        <a href="#">Home</a>
                        <span>Kokpit</span>
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
                        <h3>Twoje koplanie</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="blog-section spad">
        <div class="container">
            <div class="row">
                @foreach($mine as $mine)
                    <div class="col-lg-6">
                        <div class="blog-item">
                            <div class="row">
                                @if($mine->type==1)
                                    <div class="col-lg-4">
                                        <div class="bi-pic set-bg port_5" data-setbg="img/blog/blog-1.jpg"></div>
                                    </div>
                                @else
                                    <div class="col-lg-4">
                                        <div class="bi-pic set-bg port_6" data-setbg="img/blog/blog-1.jpg"></div>
                                    </div>
                                @endif

                                <div class="col-lg-8">
                                    <div class="bi-text">
                                        <ul>
                                            <li><i class="fa fa-calendar-o"></i>{{$mine->created_at}}</li>
                                        </ul>
                                        <h4><a href="./blog-details.html">{{$mine->name}}</a></h4>
                                        <p>{{$mine->description}}</p>
                                        <div class="bt-author">

                                            <div class="ba-text">
                                                <h5>Ilość pracowników</h5>
                                                <span
                                                    style="font-size: 20px">{{$mine->worker->count()}}/{{$mine->workers}}</span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="bt-author">

                                            <div class="ba-text">
                                                <h5>Wydobycie</h5>
                                                <span style="font-size: 20px">{{$mine->correct*0.05+0.2*($mine->worker->count())}}+({{$mine->bonus}}%) = {{round((1+($mine->bonus/100))*($mine->correct*0.05+0.2*($mine->worker->count())),2)}}</span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="bt-author">

                                            <div class="ba-text">
                                                <h5>Ulepszenia</h5>
                                                <span style="font-size: 20px">{{$mine->bonus}}%</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="blog-item">
                            <div class="row">
                                <div class="col-lg-6" style="height: 210px">
                                    <form method="POST" action="/profile/update" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group pl-4 pr-3 pt-4" style="color: white">
                                            <label for="MineName">Nazwa Kopalni</label>
                                            <input type="text" class="form-control" id="MineName" name="MineName"
                                                   autocomplete="off" required value="{{$mine->name}}">
                                        </div>
                                        <div class="form-group pl-4 pr-3" style="color: white">
                                            <label for="MineDes">Opis kopalni</label>
                                            <input type="text" class="form-control" id="MineDes" name="MineDes"
                                                   autocomplete="off" required value="{{$mine->description}}">
                                        </div>
                                        <div class="form-group pl-4 pr-3 pb-3" style="color: white; text-align: center">
                                            <input type="submit" class="btn btn-primary" value="Aktualizuj">
                                        </div>
                                        <input type="hidden" class="btn btn-primary" name="Hmine" value="{{$mine->id}}">

                                    </form>
                                </div>

                                <div class="col-lg-6 pt-3" style="height: 210px">

                                    @foreach($improvement as $imp)
                                        @if($imp->mine_id==$mine->id)
                                            <div class="pl-2 pr-3 pt-3" style="color: white">
                                                @if($imp->name=='Zwiększone zatrudnienie')
                                                    <label for="MineDes">{{$imp->name}}: +{{$mine->workers-10}}
                                                        miejsc
                                                        @else
                                                            <label for="MineDes">{{$imp->name}}: +{{$imp->value}}
                                                                %
                                                                @endif
                                                            </label>
                                            </div>
                                        @endif
                                    @endforeach

                                    <div class="form-group pl-2 pr-3 pt-3" style="color: white">
                                        <label for="MineDes">Średnia wieku ({{round($mine->worker->avg('age'),0)}}):
                                            @if($mine->worker->avg('age')<=30)
                                                0%
                                            @elseif($mine->worker->avg('age')<=40)
                                                -5%
                                            @else
                                                -15%
                                            @endif
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-12 pt-3">
                                    <div class="row">
                                        @foreach($improvement_more as $imp)

                                            @if($imp->mine_id==$mine->id)
                                                <div class="col-lg-4 p-4" style=" height: 210px">
                                                    <div class="qube p-2"
                                                         style="text-align:center; height: 100%; width: 100%; background-color: #004478;">
                                                        <form method="POST" action="/profile/mine/improve"
                                                              enctype="multipart/form-data">
                                                            @csrf

                                                            @if(($imp->max)==$imp->level)
                                                                <label for="MineDes"
                                                                       style="color: white; font-size: 12px">{{$imp->name}}</label>
                                                                <label for="MineDes" style="color: white">Maksymalny
                                                                    poziom ulepszenia został osiągnięty</label>

                                                            @else
                                                                <label for="MineDes"
                                                                       style="color: white; font-size: 12px">{{$imp->name}}</label>
                                                                <label for="MineDes" style="color: white">Cena
                                                                    : {{$imp->price}}</label>
                                                                <label for="MineDes"
                                                                       style="color: white"> {{$imp->value}}
                                                                    @if($imp->name=='Zwiększone zatrudnienie')
                                                                        miejsc pracy
                                                                    @else
                                                                        % wydobycia
                                                                    @endif
                                                                </label>
                                                                <input type="hidden" name="mine" value="{{$mine->id}}">
                                                                <input type="hidden" name="imp"
                                                                       value="{{$imp->improvement_id}}">
                                                                <input type="hidden" name="price"
                                                                       value="{{$imp->price}}">
                                                                <input type="hidden" name="value"
                                                                       value="{{$imp->value}}">
                                                                <input type="submit" class="btn btn-primary mt-2"
                                                                       value="Ulepsz">
                                                            @endif


                                                        </form>
                                                    </div>
                                                </div>

                                            @endif

                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach


                <div class="col-lg-6 offset-3">
                    <div class="blog-item">
                        <div class="row">
                            <div class="col-lg-12 pt-5" align="center">
                                <h4><a href="./blog-details.html">Zakup kopalni</a></h4>
                                @if($price==null)

                                @else
                                    <p>{{$price->value}}</p>
                                @endif
                            </div>

                            @if($price==null)

                                <div class="col-lg-12" style="height: 220px">
                                    <div align="center" class="p-4 pt-5">
                                        <h4><a href="./blog-details.html">Posiadasz już maksymalną liczbę kopalni</a>
                                        </h4>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-6" style="border-right: 1px solid white">


                                    <div align="center" class="p-4">
                                        <form method="POST" action="/profile/mine/create" enctype="multipart/form-data">
                                            @csrf
                                            <h4><a href="./blog-details.html">Kup nową kopalnię węgla kamiennego</a>
                                            </h4>
                                            <input type="hidden" name="worker" value="0"/>
                                            <input type="hidden" name="price" value="{{$price->value}}"/>
                                            <button type="submit" class="btn btn-primary mt-4">Kup</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div align="center" class="p-4">
                                        <form method="POST" action="/mine" enctype="multipart/form-data">
                                            @csrf
                                            <h4><a href="./blog-details.html">Kup nową kopalnię węgla brunatnego</a>
                                            </h4>
                                            <input type="hidden" name="worker" value="1"/>
                                            <input type="hidden" name="price" value="{{$price->value}}"/>
                                            <button type="submit" class="btn btn-primary mt-4">Kup</button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>



@endsection
