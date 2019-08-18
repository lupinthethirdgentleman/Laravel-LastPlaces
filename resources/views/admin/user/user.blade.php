 @extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/user/list.css') }}

 <!-- Widget Row Start grid -->
<div class="row" id="powerwidgets">
	<div class="col-md-12 bootstrap-grid"> 
	<!-- New widget -->
		<div class="powerwidget col-grey" id="user-directory" data-widget-editbutton="false">
			<header>
				<h2>Grid View</h2>
			</header>
			<div class="inner-spacer">
				<div id="items" class="items-switcher items-view-grid">
					
					<ul>
						<li>
							<div class="items-inner clearfix">
							  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
							  <h3 class="items-title" >Anton Durant</h3>
							  <span class="label label-danger">Banned</span>
							  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
							  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
							</div>
						</li>
						<li>
						 <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
						  <h3 class="items-title">Gluck Dorris</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						 <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
						  <h3 class="items-title">Andrew Weber</h3>
						  <span class="label label-default">Suspended</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-female.png"></a>
						  <h3 class="items-title">Adam Candler</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
						  <h3 class="items-title">Lora Shwarz</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-female.png"></a>
						  <h3 class="items-title">Dick Ulrich</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
						  <h3 class="items-title">SpiderMan</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons" > <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
						  <h3 class="items-title">Konstantin O</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
						  <h3 class="items-title">Dina Belyaeva</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
						  <h3 class="items-title">Anton Durant</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
						  <h3 class="items-title">Sandra Ruth</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
						  <h3 class="items-title">Joker</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
						  <h3 class="items-title">Konstantin O</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
						  <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
						  <h3 class="items-title">Barack Muchu</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
						<li>
					    <div class="items-inner clearfix">
						  <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
						  <h3 class="items-title">Ashur Banapal</h3>
						  <span class="label label-success">Activated</span>
						  <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
						  <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> 
							</div>
						</div>
						</li>
					</ul>   
		     <!-- End Widget --> 
				</div>
        <!-- /Inner Row Col-md-12 --> 
			</div>
      <!-- /Widgets Row End Grid-->
			<header>
				<h2>List View</h2>
			</header>
			<div id="items" class="items-switcher items-view-list">
				<ul>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
                      <h3 class="items-title" >Anton Durant</h3>
                      <span class="label label-danger">Banned</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
						</div>
						</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
                      <h3 class="items-title">Gluck Dorris</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
                      <h3 class="items-title">Andrew Weber</h3>
                      <span class="label label-default">Suspended</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                   </div>
				   </li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-female.png"></a>
                      <h3 class="items-title">Adam Candler</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                   </div>
				   </li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
                      <h3 class="items-title">Lora Shwarz</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-female.png"></a>
                      <h3 class="items-title">Dick Ulrich</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
                      <h3 class="items-title">SpiderMan</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons" > <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
                      <h3 class="items-title">Konstantin O</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                   </div>
				   </li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
                      <h3 class="items-title">Dina Belyaeva</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
                      <h3 class="items-title">Anton Durant</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                   </div>
				   </li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
                      <h3 class="items-title">Sandra Ruth</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
                      <h3 class="items-title">Joker</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
                      <h3 class="items-title">Konstantin O</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="http://placehold.it/150x150"></a>
                      <h3 class="items-title">Barack Muchu</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                    <li>
                      <div class="items-inner clearfix">
                      <a class="items-image" href="#"><img class="img-circle" src="<?php echo WEBSITE_IMG_URL; ?>images/user-male.png"></a>
                      <h3 class="items-title">Ashur Banapal</h3>
                      <span class="label label-success">Activated</span>
                      <div class="items-details"><strong>Registered:</strong> 22.11.2013 <strong>Follows:</strong> 542 <strong>Followed:</strong> 1459 <strong>Items:</strong> 4</div>
                      <div class="control-buttons"> <a href="#" title="Ban"><i class="fa fa-ban"></i></a> <a href="#" title="Delete"><i class="fa fa-times-circle"></i></a> <a href="#" title="Modify"><i class="fa fa-cog"></i></a> </div>
                    </div>
					</li>
                </ul>
            </div>
		</div>
	</div>
</div>
     
@stop