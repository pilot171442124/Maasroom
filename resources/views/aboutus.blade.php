
@extends('masterlayout')

@section('titlename') About Us @endsection

@section('maincontent')
	

		<!-- Section -->
		<section class="bg-section ysuccess pt-10 pb-10" data-black-overlay="8" style="background-image: url({{ asset('public/images/background/bg-2.jpg') }})">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-7">
						<i class="fa fa-home white"></i> <span> / About Us</span>	
					</div>
					<div class="col-lg-5 align-right">
						@if (Route::has('login'))
	                        <div class="top-right links">
	                            @auth
	                                <span>Hi,</span> <a href="{{ url('profile') }}" <span class="font-white"><u>{{ Auth::user()->name }}</u></span> </a>
	                                <a class="btn btn-primary mb-0" href="{{ url('logout') }}"><i class="fa fa-lock"></i> {{ __('Logout') }}</a>
	                            @else
	                                <a class="btn btn-info mb-0" href="{{ route('login') }}"><i class="fa fa-unlock"></i> {{ __('Login') }}</a>

	                                @if (Route::has('register'))
	                                   <a class="btn btn-success mb-0" href="{{ route('register') }}"><i class="fa fa-user-plus"></i> {{ __('Register') }}</a>
	                                @endif
	                            @endauth
	                        </div>
	                    @endif
					</div>
				</div>
			</div>
		</section>
		<!-- /Section -->	

		<!-- Testimonial Section -->
		<section class="testimonial-area pt-10 pb-10">
			<div class="container">

				<div id="formpanel" >
					<div class="row">
						<div class="col-lg-12" style="margin-bottom:130px;">
						<br/>
						<p><b>আমাদের ওয়েবসাইট এ আপনাকে স্বাগতম। </b></p><br/>
						<p><b>E-commerce System of Mushroom</b> বা ESM হচ্ছে মাশরুম ক্রয় বিক্রয় করার একটি নির্ভরযোগ্য ওয়েবসাইট। এই ওয়েবসাইটের মাধ্যমে আপনি সহজে ঘরে বসে মাশরুম ক্রয় করতে পারবেন এবং আপনার উৎপাদিত  মাশরুম বিক্রয় করতে পারবেন। আমাদের এই ওয়েবসাইটের মাধ্যমে আপনি মাশরুম সম্পর্কে জানতে পারবেন। মাশরুম খেলে কি কি উপকারিতা পাওয়া যায়, কিভাবে মাশরুম উৎপাদন করা হয় এবংমাশরুম এর বিভিন্ন প্রকার রেসিপি এর সকল তথ্য উপাধি আমাদের ব্লগ পেইজ এর মাধ্যমে জানতে পারবেন।</p>
						<br/>
						<p><b>আমাদের উদ্দেশ্য</b></p>
						<p>আমাদের মুল উদ্দেশ্য হচ্ছে মাসরুমের নতুন উদ্যোক্তা হতে উৎসাহিত করা এবং উদ্যোক্তা দের আমাদের ওয়েব সাইট এর মাধ্যমে সাহায্য করা । নতুন উদ্যোক্তারা প্রাথমিক সরংজাম আমাদের পেইজ থেকে ক্রয় করতে পারবে এবং সেগুলা ব্যবহার করে কিভাবে মাহরুম উৎপাদন করা যায় তা আমাদের পেইজে এর ব্লগের মাধ্যমে জানতে পারবে । এ ছাড়া আমাদের উদ্দেশ্য  হচ্ছে কৃষকদের উৎপাদিত পণ্য স্বল্প সময়ে এবং স্বল্প দামে আপনাদের কাছে পৌছে দেওয়া । একজন কৃষক তার পন্য স্বল্প সময়ে আমাদের পেইজের মাধ্যমে বিক্রয় করতে পারবে। কৃষক ভাইয়েরা তাদের উৎপাদিত মাসরুম বিক্রয় করার জন্য আমাদের ওয়েব সাইট ব্যবহার করতে পারবে শতকরা কম খরচে । আপনাদের এয়কৃত মাসরুম নির্দিষ্ট সময়ে পৌছে দেওয়া হবে আমাদের কাজ।</p>
					</div>
				</div>
              </div>
				
				
			</div>
		</section>
		<!-- /Testimonial Section -->
		
 @endsection


@section('customjs')

<script>
	var tableMain;
 	var SITEURL = '{{URL::to('')}}';

	$(document).ready(function() {

	
		
		//getProfileData();
	} );

function getProfileData() {

	    $.ajax({
	        type: "post",
	        url: SITEURL+"/profileviewRoute",
	        data: {
	        	"id":1,
        		"_token":$('meta[name="csrf-token"]').attr('content')
        	},
	        success:function(response){
				response = jQuery.parseJSON(response);

	        	$('#usercode').val(response[0]['usercode']);
	        	$('#name').val(response[0]['name']);
	        	$('#email').val(response[0]['email']);
	        	$('#phone').val(response[0]['phone']);
	        	$('#nid').val(response[0]['nid']);
	        	$('#address').val(response[0]['address']);
	        	//$('#password').val(response[0]['password']);
	        	$('#password').val('');
	        	$('#userrole').val(response[0]['userrole']);
	        },
	        error:function(error){
	            //alert("fail");
	            setTimeout(function() {
					toastr.options = {
						closeButton: true,
						progressBar: true,
						showMethod: 'slideDown',
						timeOut: 4000
					};
				toastr.error("Profile data can not fillup");

				}, 1300);

	        }

	    });
	}


</script>

<style>

</style>

 @endsection