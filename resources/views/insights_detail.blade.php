@extends('layout.mainlayout')

<script>
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            title: {
                text: "Insights"
            },
            axisX: {
                valueFormatString: "DD MMM YYYY"
            },
            axisY2: {
                title: "Analytics",

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
            data: [
                {
                type: "line",
                axisYType: "secondary",
                name: "Clicks",
                showInLegend: true,
                markerSize: 0,
                yValueFormatString: "#",
                dataPoints: [
                    @foreach($add->insightDetail as $detail)
                    {

                    x: new Date({{\Carbon\Carbon::create($detail->date)->format('Y,m,d')}}),
                    y: {{$detail->clicks}}
                },
                @endforeach
                ]
            }, {
                type: "line",
                axisYType: "secondary",
                name: "CPC",
                showInLegend: true,
                markerSize: 0,
                yValueFormatString: "#",
                dataPoints: [
                        @foreach($add->insightDetail as $detail)
                    {

                        x: new Date({{\Carbon\Carbon::create($detail->date)->format('Y,m,d')}}),
                        y: {{$detail->cpc}}
                    },
                    @endforeach

                    ]
            }, {
                type: "line",
                axisYType: "secondary",
                name: "Impressions",
                showInLegend: true,
                markerSize: 0,
                yValueFormatString: "#",
                dataPoints: [ @foreach($add->insightDetail as $detail)
                {

                    x: new Date({{\Carbon\Carbon::create($detail->date)->format('Y,m,d')}}),
                    y: {{$detail->impressions}}
                },
                    @endforeach]
            }, {
                type: "line",
                axisYType: "secondary",
                name: "Conversation",
                showInLegend: true,
                markerSize: 0,
                yValueFormatString: "#",
                dataPoints: [
                        @foreach($add->insightDetail as $detail)
                {

                    x: new Date({{\Carbon\Carbon::create($detail->date)->format('Y,m,d')}}),
                    y: {{$detail->clicks}}/{{$detail->impressions==0 ? 1 : $detail->impressions}}
                },
                    @endforeach
                ]
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

                                        <div class="box-shadow p-0 overflow-hidden p-3">




                                            @if($compain->goal=='SEARCH')

                                                <div class="p-3">
                                                    <div class="position-relative">
                                                    <span class="ml-2">Ad . <span
                                                            class="divurl_1"> {{$com->url}} </span></span>


                                                        <h5 class=" heading_fb  heading1_prev ml-2 mt-1"
                                                            style="color: blue!important;">{{$com->heading}}</h5>

                                                    </div>
                                                    <div class="d-flex justify-content-between ">


                                                        <p class="ml-2"> {{$com->body}}</p>
                                                    </div>
                                                </div>





                                            @elseif($compain->goal=='DISPLAY')
                                                <div class="position-relative p-3">

                                                    <img
                                                        src="{{asset('images/gallary/'.$com->image.'')}}"
                                                        class="img-fluid w-100 img1" alt="">

                                                </div>

                                            @elseif($compain->goal=='DISPLAY2')

                                                <div class="position-relative text-center">

                                                    <img src="{{asset('images/gallary/'.$com->image.'')}}" class="img-fluid w-100 img1" alt="">

                                                    <h4 class="position-absolute heading_fb text-white heading1_prev">{{$com->heading}}</h4>

                                                </div>
                                                <div class="p-3 d-flex justify-content-between ">

                                                    <p class="body1_prev">{{$com->body}}</p>
                                                    <p class="business_div" >{{$compain->business}}</p>
                                                    <a class="my-auto" target="_blank" href="{{$com->url}}"><i class="fas fa-angle-right font_icon "></i></a>
                                                </div>

                                            @endif

                                        </div>
                                    </a>
                                </div>

                            @endif


                        @endforeach
                        </div>




                        <div class="col-lg-9 mt-2">



                            <div class="row m-3 pt-5 pb-5 rounded insight_row">
                                <div class="col-12 mb-5 text-center">

                                    @if(isset($data->data[0]->status))


                                   <p class="font-weight-bolder my-3 text-danger">
                                        Status:
                                    {{$data->data[0]->effective_status}}
                                   </p>
                                    @endif
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
                                        @php
                                           $val= $add->impressions==0 ? 1 : $add->impressions
                                        @endphp

                                        <p class="mb-0 color">{{round($add->clicks / $val,2)}}</p>
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
