<div class="common_nav_top wacloudnav showheader">
    <div class="qwert" id="notification-bar">
        <div class="wacloudhead">
            <div class="container">
                <div class="row">
                  <ul class="adjustbxflex aligncenter mainMenuJw dekltop">
                    
                    <li class="logoJw mainlists adjustbxflex">
                      <div class="logosIstVals">
                        <a href="{{ url('/') }}">
                          <img width="150" height="50" src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" alt="logo">
                        </a>
                      </div>
                    </li>
                    
                    <li class="cntJw wacloudlist mainlists" id="showc">
                      <ul class="insidewacloudlist">
                         {{ PrintMenu('main-menu') }}
                          
                      </ul>
                    </li>
                    <li class="cntJw mainlists" id="showb">
                      <div class="default">
                        <a href="{{ url('/pricing') }}" class="btn_nrml brr ">{{__('Book Trial') }} </a>
                        <a id="us-button" href="{{ !Auth::check() ? url('/pricing') : url('/login') }}" class="change_us btn brdr green">
                            {{ !Auth::check() ? __('Get Started') : __('Dashboard') }}
                        </a>
                      </div>
                    </li>
                    <li class="togglejwM" id="toggleButton">
                      <svg xmlns="http://www.w3.org/2000/svg" width="48.69" height="37.87" viewBox="0 0 48.69 37.87">
                        <path id="_90_-_Menu" data-name="90 - Menu" d="M5.205,43.37h43.28a2.7,2.7,0,1,0,0-5.41H5.205a2.705,2.705,0,1,0,0,5.41ZM23.238,27.14H48.485a2.7,2.7,0,0,0,0-5.41H23.238a2.7,2.7,0,1,0,0,5.41ZM5.205,10.91h43.28a2.705,2.705,0,0,0,0-5.41H5.205a2.705,2.705,0,1,0,0,5.41Z" transform="translate(-2.5 -5.5)" fill="#00e785" />
                      </svg>
                    </li>
                  </ul>
                  <ul class="adjustbxflex aligncenter mainMenuJw nonne">
                    <li class="logoJw mainlists adjustbxflex">
                      <div class="logosIstVals">
                        <a href="{{ url('/') }}">
                          <img class="" width="150" height="50" src="{{ asset(get_option('primary_data',true)->logo ?? '') }}" alt="logo">
                        </a>
                      </div>
                    </li>
                    <li class="">
                      <div class="shq">
                        <a href="{{ url('/pricing') }}" class="btn_nrml brr">{{__('Book Trial') }}</a>
                        <a href="{{ !Auth::check() ? url('/pricing') : url('/login') }}" class="btn_nrml brr proc">{{ !Auth::check() ? __('Get Started') : __('Dashboard') }}</a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
        </div>
    </div>
</div>