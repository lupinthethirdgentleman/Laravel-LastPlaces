
<header>
  @include('element.flash_message')
	<div class="container">
    	<div class="row">
            <div class="col-sm-4"><a class="logo" href="{{ URL::to('/') }}">
              {{ HTML::image('img/logo.png', '', array('width' => '359', 'height' => '359')) }}
            </a></div>
            <div class="col-sm-8">
				<div class="header-top">
                	<div class="htop_country">
                    
                    <form method="get" onchange="this.submit()">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                      <select name="lang">
                        <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>ENGLISH</option>
                        <option value="es" {{ App::getLocale() == 'es' ? 'selected' : '' }}>ESPAÑOL</option>
                
                      </select>
                     
                    </form>
                  </div>
                	<div class="htop_email"><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i> <span>{{ Config::get('Site.contact_email') }}</span></a></div>
                	<div class="htop_call"><a href="tel:+12063350433"><i class="fa fa-phone" aria-hidden="true"></i> <span>{{ Config::get('Site.phone') }}</span></a></div>
                </div>
                <div class="clearfix"></div>
                <nav class="navbar navbar-default pull-right">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    </div>                
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav">
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('messages.destinations') }} <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <?php //echo "<pre>"; print_r($regionList); ?>
                            @if(sizeOf($regionList)>0)
                            @foreach($regionList as $rval)
                              <li class="dropdown"><a href="{{ URL::to('region-country/'.$rval->slug) }}">{{ $rval->name; }}</a>
                                <ul class="dropdown-menu">
                                @if($rval['Country']!='')
                                @foreach($rval['Country'] as $cdet)
                                    @if($cdet['active'] == 1)
                                    <li>
                                      <a href="{{ URL::to('country-trips/'.$cdet->slug) }}">{{ $cdet['name'] }}</a>
                                    </li>
                                    @endif
                                @endforeach
                                @endif
                                </ul>
                              </li>
                            @endforeach
                            @endif
                          </ul>
                        </li>
                        <?php /*
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('messages.photos') }} <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">One more separated link</a></li>
                          </ul>
                        </li> */ ?>
                        <li><a href="{{ URL::to('setdepartures') }}">{{ trans('messages.set_departures') }}</a></li>
                        <li><a href="{{ URL::to('photo-gallery/tribes') }}">{{ trans('messages.Tribes') }}</a></li>
                        <li><a href="{{ URL::to('photo-gallery/nature') }}">{{ trans('messages.Nature') }}</a></li>
                        <li><a href="{{ URL::to('contact-us') }}"> {{ trans('messages.contact_us') }}</a></li>
                        <li><a href="{{ URL::to('blogs') }}">{{ trans('messages.blog') }}</a></li>
                        <li><a href="{{ URL::to('news') }}">{{ trans('messages.news') }}</a></li>
                        
                        <!-- <li><a href="{{ URL::to('online-booking') }}">{{ trans('messages.booking') }}</a></li> -->
                       
                        <!-- <li><a href="{{ URL::to('pages/term-and-condition') }}">{{ trans('messages.term_condition') }}</a></li> -->
                        
                      </ul>
                    </div><!-- /.navbar-collapse -->
                </nav>	
            </div>
        </div>
    </div>
</header>
<script>
  $(document).ready(function() {
    $(".dropdown-toggle").dropdown();
});
  </script>