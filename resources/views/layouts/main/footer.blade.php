<!-- <footer>
    <div class="fmenu">
        <a href="{{ URL::to('pages/about-us') }}">About Us</a>
        <a href="{{ URL::to('contact-us') }}">Contact Us</a>
        <a href="{{ URL::to('pages/privacy-policy') }}">Privacy Policy</a>
        <a href="{{ URL::to('pages/term-and-condition') }}">Terms &amp; Conditions</a>
        <a href="{{ URL::to('faq') }}">FAQ</a>
         <a href="{{ URL::to('pages/help') }}">Help</a>
        <a href="{{ URL::to('pages/about-dahcp-reviews') }}">About DA/HCP Reviews </a></div>

    <div class="fsocial">
    	<a target="_blank" href="{{ Config::get('Social.facebook') }}">
    		{{ HTML::image('img/facebook.png', '', array('width' => '32', 'height' => '32')) }}
    	</a>
    	<a href="{{ Config::get('Social.twitter') }}" target="_blank">
    		{{ HTML::image('img/twitter.png', '', array('width' => '32', 'height' => '32')) }}
    	</a>
    	<a href="{{ Config::get('Social.linkedin')}}" target="_blank">
    		{{ HTML::image('img/linkedin.png', '', array('width' => '32', 'height' => '32')) }}
    	</a>
    	<a href="{{ Config::get('Social.instagram')}}" target="_blank">
    		{{ HTML::image('img/instagram.png', '', array('width' => '32', 'height' => '32')) }}
    	</a>
    	<a href="{{ Config::get('Social.gplus') }}" target="_blank">
    		{{ HTML::image('img/gplus.png', '', array('width' => '32', 'height' => '32')) }}
    	</a>
    	<a href="{{ Config::get('Social.pinterest')}}" target="_blank">
    		{{ HTML::image('img/pintrest.png', '', array('width' => '32', 'height' => '32')) }}
    	</a>
    </div>
    <div class="copyright">{{ Config::get('Site.copyright_text') }}</div>
</footer> -->

<footer>
    <div class="container footer_top">
        <div class="row">
            <div class="col-sm-3"><a class="flogo" href="{{ URL::to('/') }}">
                <img class="img-responsive" src="{{asset('img/logo.png')}}" width="359" height="359"  alt=""/>
            </a></div>
            <div class="col-sm-3">
              <h3 class="fblock_title">{{ trans('messages.company') }}</h3>
              <ul class="footer_links">
                  <li><a href="{{ URL::to('pages/about-us') }}">{{ trans('messages.about_our_company') }}</a></li>
                  <li><a href="{{ URL::to('pages/our-services') }}">{{ trans('messages.our_services') }}</a></li>
                  <li><a href="{{ URL::to('testimonial_list') }}">{{ trans('messages.testimonials') }}</a></li>
                  <li><a href="{{ URL::to('/custom-payment') }}">Custom Payment</a></li>
              </ul>
            </div>
            <div class="col-sm-3">
           	  <h3 class="fblock_title">{{ trans('messages.reservation') }}</h3>
              <ul class="footer_links">
                  <li><a href="{{ URL::to('pages/term-and-condition') }}">{{ trans('messages.term_condition') }}</a></li>
                  <li><a href="{{ URL::to('pages/refund-policy') }}">{{ trans('messages.refund_policy') }}</a></li>
                  <li><a href="{{ URL::to('pages/privacy-policy') }}">{{ trans('messages.privacy_policy') }}</a></li>
                  <li><a href="{{ URL::to('pages/disclaimer') }}">{{ trans('messages.disclaimer') }}</a></li>
                   <li><a href="{{ URL::to('online-booking') }}">{{ trans('messages.booking') }}</a></li>
              </ul>
            </div>
            <div class="col-sm-3">
           	  <h3 class="fblock_title">{{ trans('messages.contact_info') }}</h3>
                <ul class="footer_links">
                	<li>{{ Config::get('Site.address') }}</li>
                	<li><span>{{ trans('messages.phone') }}:</span> <a href="tel:{{ Config::get('Site.phone') }}">{{ Config::get('Site.phone') }}</a></li>
                  <li><span>{{ trans('messages.contact_email') }}:</span> <a href="#">{{ Config::get('Site.contact_email') }}</a></li>
                  
                	<!-- <li><span>{{ trans('messages.fax') }}:</span> 1.800.234.679</li> -->
                </ul>
            </div>
        </div>
    </div>
    <div class="copyright">
    	<div class="container">
        	<div class="row">
            	<div class="col-sm-6">{{ trans('messages.copyright') }} &copy; 2018 by <span>lastplaces</span>. {{ trans('messages.all_right_reserved') }}</div>
            	<div class="col-sm-6">
                	<ul class="fsocial">
                    	<li><a target="_blank" href="{{ Config::get('Social.facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    	<li><a target="_blank" href="{{ Config::get('Social.youtube') }}"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                    	<li><a target="_blank" href="{{ Config::get('Social.instagram') }}"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

