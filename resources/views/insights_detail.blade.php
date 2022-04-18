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
                    x: new Date(2014, 02, 01),
                    y: 890
                }, {
                    x: new Date(2014, 03, 01),
                    y: 899
                }, {
                    x: new Date(2014, 04, 01),
                    y: 903
                }, {
                    x: new Date(2014, 05, 01),
                    y: 925
                }, {
                    x: new Date(2014, 06, 01),
                    y: 899
                }, {
                    x: new Date(2014, 07, 01),
                    y: 875
                }, {
                    x: new Date(2014, 08, 01),
                    y: 927
                }, {
                    x: new Date(2014, 09, 01),
                    y: 949
                }, {
                    x: new Date(2014, 10, 01),
                    y: 946
                }, {
                    x: new Date(2014, 11, 01),
                    y: 927
                }, {
                    x: new Date(2015, 00, 01),
                    y: 950
                }, {
                    x: new Date(2015, 01, 01),
                    y: 998
                }, {
                    x: new Date(2015, 02, 01),
                    y: 998
                }, {
                    x: new Date(2015, 03, 01),
                    y: 1050
                }, {
                    x: new Date(2015, 04, 01),
                    y: 1050
                }, {
                    x: new Date(2015, 05, 01),
                    y: 999
                }, {
                    x: new Date(2015, 06, 01),
                    y: 998
                }, {
                    x: new Date(2015, 07, 01),
                    y: 998
                }, {
                    x: new Date(2015, 08, 01),
                    y: 1050
                }, {
                    x: new Date(2015, 09, 01),
                    y: 1070
                }, {
                    x: new Date(2015, 10, 01),
                    y: 1050
                }, {
                    x: new Date(2015, 11, 01),
                    y: 1050
                }, {
                    x: new Date(2016, 00, 01),
                    y: 995
                }, {
                    x: new Date(2016, 01, 01),
                    y: 1090
                }, {
                    x: new Date(2016, 02, 01),
                    y: 1100
                }, {
                    x: new Date(2016, 03, 01),
                    y: 1150
                }, {
                    x: new Date(2016, 04, 01),
                    y: 1150
                }, {
                    x: new Date(2016, 05, 01),
                    y: 1150
                }, {
                    x: new Date(2016, 06, 01),
                    y: 1100
                }, {
                    x: new Date(2016, 07, 01),
                    y: 1100
                }, {
                    x: new Date(2016, 08, 01),
                    y: 1150
                }, {
                    x: new Date(2016, 09, 01),
                    y: 1170
                }, {
                    x: new Date(2016, 10, 01),
                    y: 1150
                }, {
                    x: new Date(2016, 11, 01),
                    y: 1150
                }, {
                    x: new Date(2017, 00, 01),
                    y: 1150
                }, {
                    x: new Date(2017, 01, 01),
                    y: 1200
                }, {
                    x: new Date(2017, 02, 01),
                    y: 1200
                }, {
                    x: new Date(2017, 03, 01),
                    y: 1200
                }, {
                    x: new Date(2017, 04, 01),
                    y: 1190
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
                    x: new Date(2014, 05, 01),
                    y: 1270
                }, {
                    x: new Date(2014, 06, 01),
                    y: 1300
                }, {
                    x: new Date(2014, 07, 01),
                    y: 1300
                }, {
                    x: new Date(2014, 08, 01),
                    y: 1358
                }, {
                    x: new Date(2014, 09, 01),
                    y: 1410
                }, {
                    x: new Date(2014, 10, 01),
                    y: 1480
                }, {
                    x: new Date(2014, 11, 01),
                    y: 1500
                }, {
                    x: new Date(2015, 00, 01),
                    y: 1500
                }, {
                    x: new Date(2015, 01, 01),
                    y: 1550
                }, {
                    x: new Date(2015, 02, 01),
                    y: 1550
                }, {
                    x: new Date(2015, 03, 01),
                    y: 1590
                }, {
                    x: new Date(2015, 04, 01),
                    y: 1600
                }, {
                    x: new Date(2015, 05, 01),
                    y: 1590
                }, {
                    x: new Date(2015, 06, 01),
                    y: 1590
                }, {
                    x: new Date(2015, 07, 01),
                    y: 1620
                }, {
                    x: new Date(2015, 08, 01),
                    y: 1670
                }, {
                    x: new Date(2015, 09, 01),
                    y: 1720
                }, {
                    x: new Date(2015, 10, 01),
                    y: 1750
                }, {
                    x: new Date(2015, 11, 01),
                    y: 1820
                }, {
                    x: new Date(2016, 00, 01),
                    y: 2000
                }, {
                    x: new Date(2016, 01, 01),
                    y: 1920
                }, {
                    x: new Date(2016, 02, 01),
                    y: 1750
                }, {
                    x: new Date(2016, 03, 01),
                    y: 1850
                }, {
                    x: new Date(2016, 04, 01),
                    y: 1750
                }, {
                    x: new Date(2016, 05, 01),
                    y: 1730
                }, {
                    x: new Date(2016, 06, 01),
                    y: 1700
                }, {
                    x: new Date(2016, 07, 01),
                    y: 1730
                }, {
                    x: new Date(2016, 08, 01),
                    y: 1720
                }, {
                    x: new Date(2016, 09, 01),
                    y: 1740
                }, {
                    x: new Date(2016, 10, 01),
                    y: 1750
                }, {
                    x: new Date(2016, 11, 01),
                    y: 1750
                }, {
                    x: new Date(2017, 00, 01),
                    y: 1750
                }, {
                    x: new Date(2017, 01, 01),
                    y: 1770
                }, {
                    x: new Date(2017, 02, 01),
                    y: 1750
                }, {
                    x: new Date(2017, 03, 01),
                    y: 1750
                }, {
                    x: new Date(2017, 04, 01),
                    y: 1730
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
                    x: new Date(2014, 03, 01),
                    y: 429
                }, {
                    x: new Date(2014, 04, 01),
                    y: 429
                }, {
                    x: new Date(2014, 05, 01),
                    y: 450
                }, {
                    x: new Date(2014, 06, 01),
                    y: 450
                }, {
                    x: new Date(2014, 07, 01),
                    y: 445
                }, {
                    x: new Date(2014, 08, 01),
                    y: 450
                }, {
                    x: new Date(2014, 09, 01),
                    y: 450
                }, {
                    x: new Date(2014, 10, 01),
                    y: 440
                }, {
                    x: new Date(2014, 11, 01),
                    y: 429
                }, {
                    x: new Date(2015, 00, 01),
                    y: 435
                }, {
                    x: new Date(2015, 01, 01),
                    y: 450
                }, {
                    x: new Date(2015, 02, 01),
                    y: 475
                }, {
                    x: new Date(2015, 03, 01),
                    y: 475
                }, {
                    x: new Date(2015, 04, 01),
                    y: 475
                }, {
                    x: new Date(2015, 05, 01),
                    y: 489
                }, {
                    x: new Date(2015, 06, 01),
                    y: 495
                }, {
                    x: new Date(2015, 07, 01),
                    y: 495
                }, {
                    x: new Date(2015, 08, 01),
                    y: 500
                }, {
                    x: new Date(2015, 09, 01),
                    y: 508
                }, {
                    x: new Date(2015, 10, 01),
                    y: 520
                }, {
                    x: new Date(2015, 11, 01),
                    y: 525
                }, {
                    x: new Date(2016, 00, 01),
                    y: 525
                }, {
                    x: new Date(2016, 01, 01),
                    y: 529
                }, {
                    x: new Date(2016, 02, 01),
                    y: 549
                }, {
                    x: new Date(2016, 03, 01),
                    y: 550
                }, {
                    x: new Date(2016, 04, 01),
                    y: 568
                }, {
                    x: new Date(2016, 05, 01),
                    y: 575
                }, {
                    x: new Date(2016, 06, 01),
                    y: 579
                }, {
                    x: new Date(2016, 07, 01),
                    y: 575
                }, {
                    x: new Date(2016, 08, 01),
                    y: 585
                }, {
                    x: new Date(2016, 09, 01),
                    y: 589
                }, {
                    x: new Date(2016, 10, 01),
                    y: 595
                }, {
                    x: new Date(2016, 11, 01),
                    y: 595
                }, {
                    x: new Date(2017, 00, 01),
                    y: 595
                }, {
                    x: new Date(2017, 01, 01),
                    y: 600
                }, {
                    x: new Date(2017, 02, 01),
                    y: 624
                }, {
                    x: new Date(2017, 03, 01),
                    y: 635
                }, {
                    x: new Date(2017, 04, 01),
                    y: 650
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
                }, {
                    x: new Date(2014, 02, 01),
                    y: 539
                }, {
                    x: new Date(2014, 03, 01),
                    y: 565
                }, {
                    x: new Date(2014, 04, 01),
                    y: 575
                }, {
                    x: new Date(2014, 05, 01),
                    y: 579
                }, {
                    x: new Date(2014, 06, 01),
                    y: 589
                }, {
                    x: new Date(2014, 07, 01),
                    y: 579
                }, {
                    x: new Date(2014, 08, 01),
                    y: 579
                }, {
                    x: new Date(2014, 09, 01),
                    y: 579
                }, {
                    x: new Date(2014, 10, 01),
                    y: 569
                }, {
                    x: new Date(2014, 11, 01),
                    y: 525
                }, {
                    x: new Date(2015, 00, 01),
                    y: 535
                }, {
                    x: new Date(2015, 01, 01),
                    y: 575
                }, {
                    x: new Date(2015, 02, 01),
                    y: 599
                }, {
                    x: new Date(2015, 03, 01),
                    y: 619
                }, {
                    x: new Date(2015, 04, 01),
                    y: 639
                }, {
                    x: new Date(2015, 05, 01),
                    y: 648
                }, {
                    x: new Date(2015, 06, 01),
                    y: 640
                }, {
                    x: new Date(2015, 07, 01),
                    y: 645
                }, {
                    x: new Date(2015, 08, 01),
                    y: 648
                }, {
                    x: new Date(2015, 09, 01),
                    y: 649
                }, {
                    x: new Date(2015, 10, 01),
                    y: 649
                }, {
                    x: new Date(2015, 11, 01),
                    y: 649
                }, {
                    x: new Date(2016, 00, 01),
                    y: 650
                }, {
                    x: new Date(2016, 01, 01),
                    y: 665
                }, {
                    x: new Date(2016, 02, 01),
                    y: 675
                }, {
                    x: new Date(2016, 03, 01),
                    y: 695
                }, {
                    x: new Date(2016, 04, 01),
                    y: 690
                }, {
                    x: new Date(2016, 05, 01),
                    y: 699
                }, {
                    x: new Date(2016, 06, 01),
                    y: 699
                }, {
                    x: new Date(2016, 07, 01),
                    y: 699
                }, {
                    x: new Date(2016, 08, 01),
                    y: 699
                }, {
                    x: new Date(2016, 09, 01),
                    y: 699
                }, {
                    x: new Date(2016, 10, 01),
                    y: 709
                }, {
                    x: new Date(2016, 11, 01),
                    y: 699
                }, {
                    x: new Date(2017, 00, 01),
                    y: 700
                }, {
                    x: new Date(2017, 01, 01),
                    y: 700
                }, {
                    x: new Date(2017, 02, 01),
                    y: 724
                }, {
                    x: new Date(2017, 03, 01),
                    y: 739
                }, {
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
                                    <a href="{{url('manage_detail/'.$compain->id.'')}}" class="a_card">

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
                                            <div class="pt-0 pb-0">

                                                <img src="{{asset('images/ads/'.$com->image.'')}}" class="img-fluid" alt="">

                                            </div>
                                            <div class="bg_gray d-flex p-2 justify-content-between">
                                                <div>
                                                    {{--                                    <h6 class="gray mb-0">Demo</h6>--}}
                                                    <p class="text-black-50">{{$com->body}}</p>
                                                </div>
                                                <div class="my-auto">
                                                    <a href="{{$com->url}}" target="_blank" class="btn btn-secondary learn">{{$compain->action_btn}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            @else


                                <div class="col-12 mt-3">
                                    <a href="{{url('manage_detail/'.$compain->id.'')}}" class="a_card">

                                        <div class="box-shadow p-0 overflow-hidden ">



                                            <div class="position-relative">

                                                <img src="{{asset('images/ads/'.$com->image.'')}}" class="img-fluid" alt="">

                                                <h4 class="position-absolute heading_fb text-white">{{$com->heading}}</h4>

                                            </div>
                                            <div class="p-3 d-flex justify-content-between ">


                                                <p>{{$com->body}}</p>
                                                <a href="{{$compain->url}}" target="_blank" class="my-auto"><i class="fas fa-angle-right font_icon "></i></a>
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
                                        <p class="mb-0 color">5</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-12 mt-2">
                                    <div class="bg-insight text-center">
                                        <h5>Impressions</h5>
                                        <p class="mb-0 color">5</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-12 mt-2">
                                    <div class="bg-insight text-center">
                                        <h5>CPC</h5>
                                        <p class="mb-0 color">5</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-12 mt-2">
                                    <div class="bg-insight text-center">
                                        <h5>Conversation</h5>
                                        <p class="mb-0 color">5</p>
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
