<!DOCTYPE html>
<html>
	<head>
		<title><?php echo Config::get("Site.title"); ?></title>
		
		{{ HTML::style('css/admin/bootstrap.css') }}
		{{ HTML::style('css/admin/font-awesome/css/font-awesome.css') }}
		{{ HTML::style('css/admin/mws-style.css') }}
		{{ HTML::style('css/admin/icons/icol16.css') }}
		{{ HTML::style('css/admin/fonts/icomoon/style.css') }}
		{{ HTML::style('css/admin/icons/icol32.css') }}
		{{ HTML::style('css/admin/mws-theme.css') }}
		{{ HTML::style('css/admin/themer.css') }}
		{{ HTML::style('css/admin/custom.css') }}
		{{ HTML::style('css/admin/jquery-ui.css') }}	
		
		{{ HTML::script('js/admin/jquery-2.1.1.min.js') }}
		{{ HTML::script('js/admin/core/mws.js') }}
		{{ HTML::script('js/admin/core/themer.js') }}
		{{ HTML::script('js/admin/bootstrap/js/bootstrap.min.js') }}
		{{ HTML::script('js/admin/jquery-ui.min.js') }}
		
		
		{{ HTML::style('css/admin/styleres.css') }}
		{{ HTML::style('css/admin/custom_res.css') }}
		
		<!-- js and css For multiple delete,active,inactive -->
		{{ HTML::script('js/admin/bootbox.js') }}
		{{ HTML::script('js/bootstrap.min.js') }}
		{{ HTML::style('css/admin/bootmodel.css') }}
		
		<script type="text/javascript">
			/* For set the time for flash messages */
			$(function(){
			
				window.setTimeout(function () { 
					$(".mws-form-message.success").hide('slow'); 
					$(".mws-form-message.error").hide('slow'); 
				}, 6000);
				
			});
		</script>
		<style>
			.fa {  margin:0 8px; }
			.modal-backdrop {
				z-index: 0;
			}
			.close{
				background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
				border: 0 none;
				cursor: pointer;
				padding: 0;
				color: #000;
				float: right;
				font-size: 24px;
				font-weight: 700;
				line-height: 1;
				opacity: 0.2;
				text-shadow: 0 1px 0 #fff;
			}
			.highlightBox {
				border: 1px solid red !important;
				box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
			}
			.requireRed{
			color:red;
			}
		</style>
	</head>
	<body>
		<div id="mws-header" class="clearfix">
			<div id="mws-logo-container">
				<div id="mws-logo-wrap" style="color:#ffffff; font-size:26px;">
					<a href="{{URL::to('admin/dashboard')}}" style="text-decoration:none; color:white;"><?php echo Config::get("Site.title"); ?></a>
				</div>
			</div>
			<div id="mws-user-tools" class="clearfix">
				<div id="mws-user-info" class="mws-inset" style="padding:0; height:36px">
					 <div id="mws-user-functions">
						<div id="mws-username">
							{{trans("messages.dashboard.hello")}} {{ Auth::user()->full_name}}!
						</div>
						<ul>
							<li><a href="{{URL::to('admin/myaccount')}}">{{ trans("messages.dashboard.edit_profile") }} </a></li>
							<li><a href="{{URL::to('admin/logout')}}">{{ trans("messages.dashboard.logout") }} </a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- Start Main Wrapper -->
		<div id="mws-wrapper">
			<div id="mws-sidebar">
				<div id="mws-nav-collapse">
					<span></span>
					<span></span>
					<span></span>
				</div>
				<?php $segment2	=	Request::segment(2); ?>
				<!-- Sidebar Wrapper -->
				<div id="mws-navigation">
					<ul>
						<!-- <li class="{{ ($segment2 == 'dashboard') ? 'active in' : '' }}"><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-home fa-2x "></i>{{ trans("messages.system_management.dashboard") }} </a></li> -->
						
						 <li class="{{ ($segment2 == 'users') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/users')}}"><i class="fa fa-users fa-2x"></i>{{ trans("messages.user_management.user_management") }} </a>
						</li> 
					
						<li class="{{ ($segment2 == 'cms-manager') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/cms-manager')}}"><i class="fa fa-file fa-2x"></i>{{ trans("messages.system_management.cms_pages") }} </a>
						</li>

					<li class="{{ in_array($segment2 ,array('email-manager','location-manager','faqs-manager','news-letter','menus','block-manager','slider-manager','language-settings','meta-manager','contact-manager','email-logs','testimonial-manager','ads-manager','system-doc-manager','location')) ? 'in' : 'offer-reports' }}">
						
							<a href="javascript::void(0)"><i class="fa fa-th fa-2x"></i>{{ trans("messages.system_management.system_management") }} </a>
							<ul class="{{ in_array($segment2 ,array('email-manager','menus','block-manager','faqs-manager','news-letter','slider-manager','language-settings','meta-manager','contact-manager','email-logs','email-logs','testimonial-manager','ads-manager','system-doc-manager','location','enquiry')) ? '' : 'closed' }}">
								<li @if($segment2=='contact-manager') class="active" @endif><a href="{{URL::to('admin/contact-manager')}}">{{ trans("messages.system_management.contacts") }} </a></li>
								<li @if($segment2=='block-manager') class="active" @endif><a href="{{URL::to('admin/block-manager')}}">{{ trans("messages.system_management.blocks") }} </a></li>
<!-- 								<li @if($segment2=='enquiry-manager') class="active" @endif><a href="{{URL::to('admin/enquiry-manager')}}">{{ trans("messages.system_management.enquiry") }} </a></li>
 -->							</ul>
						</li>

						<?php  ?>
						
						<li class="{{ in_array($segment2 ,array('settings','text-setting','HeaderSetting')) ? 'active in' : 'offer-reports' }}">
							<a href="javascript::void(0)"><i class="fa fa-cogs fa-2x "></i>{{ trans("messages.system_management.settings")  }} </a>
							<ul class="{{ in_array($segment2 ,array('settings','text-setting')) ? '' : 'closed' }}">
								<li>
									<a href="{{URL::to('admin/settings/prefix/Site')}}">{{ trans("messages.settings.site_setting") }} </a>
								</li>
																<li @if($segment2=='text-setting' && Request::segment(3) == 1 || Request::segment(4) == 1) class="active" @endif>
									<a href="{{ route('TextSetting.index',1)}}">{{ trans("Text Setting") }} </a>
								</li>


								<li @if($segment2=='settings' && Request::segment(4)=='Social') class="active" @endif >
									<a href="{{ route('Setting.prefix_index','Social')}}">{{ trans("Social") }} </a>
								</li>
								<li @if($segment2=='settings' && Request::segment(4)=='Reading') class="active" @endif>
									<a href="{{ route('Setting.prefix_index','Reading') }}">{{ trans("Reading") }} </a>
								</li>
								<li @if($segment2=='settings' && Request::segment(4)=='HeaderSetting') class="active" @endif >
									<a href="{{ route('HeaderSetting.edit','392') }}">{{ trans("Header Image") }} </a>
								</li>
							</ul>
						</li>
						
						<!-- 
	 					<li class="{{ ($segment2 == 'destinations') ? 'active in' : 'offer-reports' }}">
							<a href="{{URL::to('admin/destinations')}}">
								<i class="fa fa-road fa-2x"></i>
								{{ trans("messages.destinations.destination") }} 
							</a>
						</li>  -->


						<?php   ?>

						<li class="{{ ($segment2 == 'language') ? ' in' : '' }}">
							<a href="javascript:void(0)">
								<i class="fa fa-language fa-2x "></i>
								{{ trans("Languages") }}
							</a>
							<ul class="{{ in_array($segment2 ,array('jsconstant-setting','language')) ? '' : 'closed' }}">
																<li @if($segment2=='language' ) class="active" @endif >
									<a href="{{ route('Language.index')}}">{{ trans("Language ") }}</a>
								</li>
								
							</ul>
						</li>

						<li class="{{ ($segment2 == 'language') ? ' in' : '' }}">
							<a href="{{ route('Testimonial.index')}}">
								<i class="fa fa-comments fa-2x "></i>
								{{ trans("Testimonials") }}
							</a>
						</li>

						<li class="{{ in_array($segment2 ,array('region-manager','trip-manager')) ? 'in' : 'offer-reports' }}">
							<a href="javascript::void(0)"><i class="fa fa-road fa-2x "></i>{{ trans("messages.destination_management.settings")  }} </a>
							<ul class="{{ in_array($segment2 ,array('region-manager','trip-manager','list-destination-country','list-trip-package','list-trip-status','trip-reviews')) ? '' : 'closed' }}">
								<li @if($segment2=='region-manager') class="active" @endif>
									<a href="{{ route('Region.index')}}">{{ trans("messages.destination_management.regions") }} </a>
								</li>
								<li @if($segment2=='list-destination-country') class="active" @endif>
									<a href="{{ route('DestinationCountry.index')}}">{{ trans("messages.destination_management.country") }} </a>
								</li>

								<li @if($segment2=='trip-manager') class="active" @endif>
									<a href="{{ route('Trip.index')}}">{{ trans("messages.destination_management.trip") }} </a>
								</li>
								<li @if($segment2=='list-trip-package') class="active" @endif>
									<a href="{{ route('TripPackage.index')}}">{{ trans("Trip Package") }} </a>
								</li>
								<li @if($segment2=='list-trip-status') class="active" @endif>
									<a href="{{ route('TripStatus.index')}}">{{ trans("Trip Status") }} </a>
								</li>
								<li @if($segment2=='trip-reviews') class="active" @endif>
									<a href="{{ route('Tripreview.index')}}">{{ trans("Trip Reviews") }} </a>
								</li>

							</ul>
						</li>

						<li class="{{ ($segment2 == 'blog-manager') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/blog-manager')}}"><i class="fa fa-newspaper-o	 fa-2x"></i><!-- {{ trans("messages.system_management.cms_pages") }}  -->Blog Manager</a>
						</li>

						<li class="{{ ($segment2 == 'news-manager') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/news-manager')}}"><i class="fa fa-newspaper-o	 fa-2x"></i><!-- {{ trans("messages.system_management.cms_pages") }}  -->News Manager</a>
						</li>

						<li class="{{ ($segment2 == 'photo-manager') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/photo-manager/list')}}"><i class="fa fa-image fa-2x"></i><!-- {{ trans("messages.system_management.cms_pages") }}  -->Photo Manager</a>
						</li>
						<li class="{{ ($segment2 == 'photo-country-manager') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/photo-country-manager/list')}}"><i class="fa fa-image fa-2x"></i><!-- {{ trans("messages.system_management.cms_pages") }}  -->Photo Country Manager Tribes</a>
						</li>
						<li class="{{ ($segment2 == 'photo-country-manager-nature') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/photo-country-manager-nature/list')}}"><i class="fa fa-image fa-2x"></i><!-- {{ trans("messages.system_management.cms_pages") }}  -->Photo Country Manager Nature</a>
						</li>

						<li class="{{ $segment2 == 'payment-manager' ? 'active in' : '' }}">
							<a href="{{URL::to('admin/payment-manager')}}"><i class="fa fa-newspaper-o fa-2x {{ $segment2 == 'payment-manager' ? 'fa-spin' : '' }}"></i>{{ 'Package Bookings' }} </a>
						</li>	

					</ul>
				</div>         
			</div>
			  <!-- Main Container Start -->
			<div id="mws-container" class="clearfix">
				<div class="container" style="min-height:600px">
					<p></p>
					@if(Session::has('error'))
					<div class="grid_8 mws-collapsible">
						<div class="mws-form-message error">
							<a href="javascript:void(0);" class="close pull-right">&times;</a>
							<p>{{ Session::get('error') }}</p>
						</div>
					</div>
					<div class="clearfix">&nbsp;</div>
					@endif
					@if(Session::has('success'))
					<div class="grid_8 mws-collapsible">
						<div class="mws-form-message success">
							<a href="javascript:void(0);" class="close pull-right">&times;</a>
							<p>{{ Session::get('success') }}</p>
						</div>
					</div>
					<div class="clearfix">&nbsp;</div>
					@endif

					@if(Session::has('flash_notice'))
					<div class="grid_8 mws-collapsible">
						<div class="mws-form-message success">
							<a href="javascript:void(0);" class="close pull-right">&times;</a>
							<p>{{ Session::get('flash_notice') }}</p>
						</div>
					</div>
					<div class="clearfix">&nbsp;</div>
					@endif
					{{ isset($breadcrumbs) ? $breadcrumbs : ''}}
					
					@yield('content')
				</div>
			</div>
		</div>
	
		<div id="mws-footer-fix" class="mws-footer-fix clearfix">
			 <div id="mws-user-tools" class="clearfix" style="padding:5px 0;">
				<div id="mws-user-info" class="mws-inset" style="height:18px; padding:0">
					 <div id="mws-user-functions">
						<div id="mws-username" >
							<?php echo Config::get("Site.copyright_text"); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
