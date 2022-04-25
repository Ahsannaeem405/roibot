@extends('layout.mainlayout')

<script>
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            title: {
                text: "Insights"
            },
            axisX: {
                valueFormatString: "MMM YYYY"
            },
            axisY2: {
                title: "Analytics",
                prefix: "$",
                suffix: "K"
            },
            toolTip: {
                shared: true
            },
            legend: {
                cursor: "pointer",
                verticalAlign: "top",
                horizontalAlign: "center",
                dockInsidePlotArea: true,
                itemclick: toogleDataSeries
            },
            data: [{
                type: "line",
                axisYType: "secondary",
                name: "Clicks",
                showInLegend: true,
                markerSize: 0,
                yValueFormatString: "$#,###k",
                dataPoints: [{
                    x: new Date(2014, 00, 01),
                    y: 850
                }, {
                    x: new Date(2014, 01, 01),
                    y: 889
                }, {
                    x: new Date(2017, 05, 01),
                    y: 1170
                }]
            }, {
                type: "line",
                axisYType: "secondary",
                name: "CPC",
                showInLegend: true,
                markerSize: 0,
                yValueFormatString: "$#,###k",
                dataPoints: [{
                    x: new Date(2014, 00, 01),
                    y: 1200
                }, {
                    x: new Date(2014, 01, 01),
                    y: 1200
                }, {
                    x: new Date(2014, 02, 01),
                    y: 1190
                }, {
                    x: new Date(2014, 03, 01),
                    y: 1180
                }, {
                    x: new Date(2014, 04, 01),
                    y: 1250
                }, {
                    x: new Date(2017, 05, 01),
                    y: 1730
                }]
            }, {
                type: "line",
                axisYType: "secondary",
                name: "Impressions",
                showInLegend: true,
                markerSize: 0,
                yValueFormatString: "$#,###k",
                dataPoints: [{
                    x: new Date(2014, 00, 01),
                    y: 409
                }, {
                    x: new Date(2014, 01, 01),
                    y: 415
                }, {
                    x: new Date(2014, 02, 01),
                    y: 419
                }, {
                    x: new Date(2017, 05, 01),
                    y: 675
                }]
            }, {
                type: "line",
                axisYType: "secondary",
                name: "Conversation",
                showInLegend: true,
                markerSize: 0,
                yValueFormatString: "$#,###k",
                dataPoints: [{
                    x: new Date(2014, 00, 01),
                    y: 529
                }, {
                    x: new Date(2014, 01, 01),
                    y: 540
                },  {
                    x: new Date(2017, 04, 01),
                    y: 749
                }, {
                    x: new Date(2017, 05, 01),
                    y: 740
                }]
            }]
        });
        chart.render();

        function toogleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }

    }
</script>
@section('content')
    <!-- sidebar section -->
    <section class="section">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12 pt-5">

                    <div class="row m-3 mt-2 pb-5 rounded insight_row">

                        <div class="col-lg-3">



                        @foreach($compain->activeAdd as $com)

                            @if($compain->type==1)
                                <div class="col-md-12 mt-3">
                                    <a href="{{url('insight_detail/'.$compain->id.'/'.$com->id.'')}}" class="a_card">

                                        <div class="box-shadow p-3">

                                            <div class="d-flex">
                                                <div>
                                                    <img src="{{asset('images/img_avatar.png')}}" class="rounded-circle" width="50" alt="">
                                                </div>
                                                <div class="ml-3">
                                                    <h5 class="mb-0">{{Auth::user()->name}}</h5>
                                                    <p class="gray mb-0">Sponsored <i class="fas fa-globe"></i></p>

                                                    <p class="text-justify">{{$com->heading}} </p>
                                                </div>
                                            </div>
                                            <div class="pt-0 pb-0 text-center">

                                                <img src="{{asset('images/gallary/'.$com->image.'')}}" class="img-fluid" alt="">

                                            </div>
                                            <div class="bg_gray d-flex p-2 justify-content-between">
                                                <div>
                                                    {{--                                    <h6 class="gray mb-0">Demo</h6>--}}
                                                    <p class="text-black-50">{{$com->body}}</p>
                                                </div>
                                                <div class="my-auto">
                                                    <a href="{{$com->url}}" target="_blank" class="btn btn-secondary learn">{{$com->button}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            @else


                                <div class="col-12 mt-3">
                                    <a href="{{url('insight_detail/'.$compain->id.'/'.$com->id.'')}}" class="a_card">

                                        <div class="box-shadow p-0 overflow-hidden ">



                                            <div class="position-relative text-center">

                                                <img src="{{asset('images/gallary/'.$com->image.'')}}" class="img-fluid" alt="">

                                                <h4 class="position-absolute heading_fb text-white">{{$com->heading}}</h4>

                                            </div>
                                            <div class="p-3 d-flex justify-content-between ">


                                                <p>{{$com->body}}</p>
                                                <a href="{{$com->url}}" target="_blank" class="my-auto"><i class="fas fa-angle-right font_icon "></i></a>
                                            </div>

                                        </div>
                                    </a>
                                </div>

                            @endif


                        @endforeach
                        </div>




                        <div class="col-lg-9 mt-2">

                            <div class="row m-3 pt-5 pb-5 rounded insight_row">
                                <div class="col-12 mb-5 text-center">
                                    <h3 class="color">Insights</h3>
                                </div>
                                <div class="col-md-6 col-lg-3 col-12 mt-2">
                                    <div class="bg-insight text-center">
                                        <h5>Clicks</h5>
                                        <p class="mb-0 color">{{$add->clicks}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-12 mt-2">
                                    <div class="bg-insight text-center">
                                        <h5>Impressions</h5>
                                        <p class="mb-0 color">{{$add->impressions}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-12 mt-2">
                                    <div class="bg-insight text-center">
                                        <h5>CPC</h5>
                                        <p class="mb-0 color">{{$add->cpc}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-12 mt-2">
                                    <div class="bg-insight text-center">
                                        <h5>Conversation</h5>
                                        <p class="mb-0 color">{{$add->conversation}}</p>
                                    </div>
                                </div>
                                <div class="col-12 pt-5">
                                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                                </div>
                            </div>


                        </div>


                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- end sidebar section -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

@endsection
