@extends('layouts.inner')
@section('content')
<style>
.lastplaces-divider-inner{
	width:100%;
	border-color:#c6b689;
	border-top-width:1px;
	border-bottom-width:1px;
	height:1px;
	border-style: dashed;
}
.lastplaces_seperator{
	padding-top: 20px;
  padding-bottom: 20px;
  font-size: 14px;

  font-style: normal;

  font-weight: 400;
}
.with-errors{
  color:#B22222;
}
</style>
{{ HTML::style('css/datepicker.css') }}
{{ HTML::script('js/bootstrap-datepicker.js') }}
<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4">
                <h1>{{trans('messages.custom_payment')}}</h1> 
            </div>
        </div>
    </div>
</div>

<div class="container">
	<div class="form_container" style="width:80%;" id="scroll_error_signup">
        <div id="error_div_signup" style="display:none"></div>
        {{ Form::open(['role' => 'form','url' => 'save-custom-payment','id'=>'bookingenquiry_form','name'=>'bookingenquiry_form','method'=>'post','class' => 'mws-form', 'files' => true]) }}
    		<div class="section_heading">
    			<div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">{{ trans('messages.personalinformation') }}</div>
    		</div>

    		<div class="section_form">
            <div class="form-group">
              <div class="row">
                  <div class="col-sm-12">
                  {{ Form::email(
                          'email', 
                          null,
                          ['class'=>'form-control','placeholder'=>trans('messages.email'),'required'=>'required','data-errormessage-value-missing' => 'Email is required.']
                          ) 
                      }}  
                  </div>                  
              </div>
            </div>
			      <div class="form-group">
              <div class="row">
                  <div class="col-sm-12">
                  {{ Form::number(
                          'custom_amount', 
                          null,
                          ['class'=>'form-control','placeholder'=>trans('messages.amount'),'required'=>'required','maxlength'=>6,'data-errormessage-value-missing' => 'Amount is required.']
                          ) 
                      }}  
                  </div>                  
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                  <div class="col-sm-12">
                                {{ Form::textarea(
                                    'custom_description', 
                                    null,
                                    ['class'=>'form-control','placeholder'=>trans('messages.description'),'required'=>'required','data-errormessage-value-missing' => 'Description is required','id'=>'description','rows'=>'4']
                                    ) 
                                }}  
                                <div class="help-block with-errors"><?php echo $errors->first('description'); ?></div>  
                      </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                  <div class="col-sm-12">
                                {{ Form::checkbox('agree', 0, null,
                                ['required'=>'required','data-errormessage-value-missing' => 'Description is required']) }}
                                {{ Form::label('agree', '&nbsp;I agree to the ')}} <a href="{{ URL::to('pages/term-and-condition') }}">{{ trans('messages.term_condition') }}</a> 
                                <div class="help-block with-errors">{{ $errors->first('agree') }} </div>  
                      </div>
              </div>
            </div>

					</div>

    				  <div class="lastplaces_seperator">
                	<div class="lastplaces-divider-inner"></div>
               </div>

                <div class="form-group">
                <div class="row">
                  <div class="col-sm-12">
                     <input type="submit" id="redsys_pay" value="{{ trans('messages.confirmyourbookingwithRedsys') }}" class="btn btn-primary">

                     <!-- <input type="submit" id="redsys_pay_hidden" value="{{ trans('messages.confirmyourbooking') }}" class="btn btn-primary" style="display:none;"> -->
                  </div>
                </div>
              </div>
                    <!--  Block for Calculating the amount -->
        {{ Form::close() }}
      	</div>
    	</div>
</div>
@include('layouts.main.footer_top')
@stop