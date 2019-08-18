<?php $i=1; ?>

@if(sizeof($filterdetails) > 0)

@foreach($filterdetails as $tripsdetails)

                        <li class="hbl_item">
                            <div class="row">
                                <div class="col-sm-4 col-lg-3 col-md-3">
                                <div class="hbl_item_img-box">
                                    <a href="{{ URL::to('trips/'.''.$tripsdetails->name.'/'.$tripsdetails->slug) }}">
                                        <img src="<?php echo WEBSITE_URL.'image.php?width=163px&height=163px&image='.TRIP_IMAGE_URL.$tripsdetails->image;?>" alt="" />
                                    </a>
                                </div>
                                </div>
                                <div class="col-sm-8 col-lg-9 col-md-9">
                                 
                                    <section class="bigpost_description_heading">
                                        <a href="{{ URL::to('trips/'.''.$tripsdetails->name.'/'.$tripsdetails->slug) }}">
                                                {{str_limit($tripsdetails->tripname, 150)}}
                                        </a>
                                    </section>
                                    <section class="bigpost_description">
                                            {{str_limit($tripsdetails->description, 140)}}
                                    </section>

                                     <section class="bigpost_top">
                                        <div class="bigpost_time"><i class="fa fa-clock-o" aria-hidden="true"></i>
                                           <?php 
                                            $tripdates=date('Y',strtotime($tripsdetails->tripdates));
                                            if($tripdates=="1970"){$tripdates='';}else{ $tripdates=date('M d, Y',strtotime($tripsdetails->tripdates));}?>
                                            {{$tripdates}}
                                        </div>
                                    <div class="bigpost_comments"><i class="fa fa-calendar" aria-hidden="true"></i> 
                                        {{$tripsdetails->tripdays}} {{ trans('messages.days') }}
                                    </div>
                                    <div class="bigpost_comments"><i class="fa fa-euro" aria-hidden="true"></i> 
                                        {{ $tripsdetails->baseprice}}
                                    </div>
                                    <!-- <button type="button" class="btn btn-info comment-btn" data-toggle="collapse" data-target="#demo_{{$i}}">{{trans('messages.clicktocomment')}}</button>  -->
                                     
                                    </section> 

                                </div>
                            </div>
                        </li>
                    <?php $i++ ; ?>
                    @endforeach

@else
        <p>No Data Found</p>
@endif