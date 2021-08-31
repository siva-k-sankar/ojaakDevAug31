<!-- Footer -->
    <footer class="footer_outer_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-6">
                    <div class="footer_logo_wrap">
                        <img src="{{ asset('public/frontdesign/assets/img/ojaak_logo.png') }}" alt="ojaak logo" title="ojaak logo">
                    </div>
                    <div class="company_desc_wrap">
                        <p>OJAAK is India’s classified app to buy / sell products. We passionately believe that our marketplace should provide profit for the active customers when they are ready to spend their quality time with us. Since 2019 OJAAK provides the virtual marketplace to connect the trusted buyers and sellers.
                        </p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="footer_common_outer_wrap">
                        <h2>Company</h2>
                        <ul>
                            <li><a href="{{route('aboutus')}}">About Us</a></li>
                            <li><a href="{{route('howitswork')}}">How it works</a></li>
                            <li><a href="{{route('help')}}">Help</a></li>
                            <li><a href="{{route('contact')}}">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="footer_common_outer_wrap">
                        <h2>Info</h2>
                        <ul>
                            <li><a href="{{route('faq')}}">FAQ's</a></li>
                            <li><a href="{{route('privacypolicy')}}">Privacy Policy</a></li>
                            <li><a href="{{route('termscondition')}}">Terms & Conditions</a></li>
                            <li><a href="{{route('sitemap')}}">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="footer_common_outer_wrap">
                        <h2>Social Media</h2>
                        <ul>
                            <li><a href="{{$Footer_setting->facebook}}">Facebook</a></li>
                            <li><a href="{{$Footer_setting->twitter}}">Twitter</a></li>
                            <li><a href="{{$Footer_setting->linkedin}}">Pinterest</a></li>
                            <li><a href="{{$Footer_setting->instagram}}">Instagram</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row bottom_bar_wrap">
                <div class="col-xl-7 col-lg-6 col-md-5">
                    <p>{{$Footer_setting->footer}}</p>
                </div>
                <div class="col-xl-5 col-lg-6 col-md-7">
                    <div class="download_app_outer_wrap">
                        <h2>Download our app</h2>
                        <a href="https://play.google.com/">
                            <img src="{{asset('public/frontdesign/assets/img/download_playstore.png')}}">
                        </a>
                        <a href="https://www.apple.com/in/ios/app-store/">
                            <img src="{{asset('public/frontdesign/assets/img/download_appstore.png')}}">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script language="JavaScript" type="text/javascript" >
    var APP_URL = "{{(url('/'))}}"; 
    </script>
    <!-- @if(Auth::check())
        <script language="JavaScript" type="text/javascript" >
            var Check_user_status= "{{Auth::user()->status}}";
            if(Check_user_status=="2" || Check_user_status=="3" || Check_user_status=="0"){ 
                $.ajax({
                    type: 'get',
                    url: APP_URL+'/'+'/ajaxlogout',
                    dataType: 'json',
                    beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success: function(response)
                    {
                        //location.reload(); // but it is GET request... should be POST request
                        location.href = APP_URL;
                    }
                });
            }
    </script>
    @endif -->