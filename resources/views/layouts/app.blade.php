<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{ Html::script('js/bootstrap.min.js') }}
    {{ Html::script('js/jquery.easy-autocomplete.min.js') }}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    {{ Html::style('css/fontawesome.min.css') }}

    <!-- Styles -->
    {{ Html::style('css/bootstrap.min.css') }}
    {{ Html::style('css/easy-autocomplete.min.css') }}
    {{ Html::style('css/easy-autocomplete.themes.min.css') }}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

@yield('head')

<!--

                                                                                               ```.`.``     ``````.``    ```.`.```  ``````
                                                                                           ``  `````..```    `.`.`..``   `.````.``  ``.....```
                                                                                       ```````  ````.`````    `..``````  `.```.```  ``````.``
                                                                                        ``.`.``   `..``.```   `````...````````````  ``````.``
                                                                           `             `....``   `..`.`.```  `.``...`.``.```..``  `````````
                                                                         ````````        `````      ``.`.```````````...`.````.`.``  ```.````
                                                          ``````````````````.````````     ``   ``    ``.`.``....```.`.....`......`   ````    `````
                                                              ``````.````````                ```.```  ``....``````.``...``````..```      ````.``.``
                                                                   ````````                 ``..``.``` ``.`````....````    ```````.`  `````..``...``
                                                                        `                `  ``....``.`````...`````    ````.`..````..``.`.`.`````````
                                                                                        ````  `````..`.``..```    `````..````   ``.``..`````...``
                                                                                       ``..```  ````..```.```````.```.`.`  ```````.``.````..```
                                                                                      ``````````  ```...```.```.`.``.`.`  `....`.```````````
                                                                                          ````````  ````.``.```.````..`` ``````````````.`    ``
                                                                                             ``..```  ``..```````````.`  `````````````.`   ``.``
                                                                                              `....```  `````````````````...`````..`..``  ```.```
                                                                                               ``.``.```  ````..````.`..````.``..``.``   `..```
                                                                                               `..``.`.```  `````.``.``.....`..````    ````
                                                                                               `.``````..```  ```...```.`````       `````
                                                                                               `````.````..```  ``````        `````````
                                                                                                `     ``````````       ````````....```
                                                                                                            ``..`````````````````````
                                                                                                              ````````
                                                                                                               ````                  .+`+`
                                                                                                                                    .:--`////-
                                                                                       `////////////++/.       :shmNNNNNds:`     `//-::..    ...::`
                --           `:.      `+:                                           `/+ds++++++//:.   .d-    /mMMN:dMMMMMMMm/    ..`../+///:-.- .`/.
                 +hho  :y:s/++yo`     .Nd         sh-                       oy-    `h-          `./o.  /o   sMMMN: `dMMMMMMMMs`  `h`./-     -/:.--`.
                    -..o+-NN/-MN-:` -+oNm:-.`   +hNMyo+: -- :yyssssd++` ::::dMs::-` ./oo/:::::::+syo+/:oo  /MMMMy-  `dMMMMMMMMs  /s :+/     //. `o
                    /h+mNo+Mm+MN:NN:-oyMNddmy   .:mMhoo+`mN..ssssyydsm.`hdddmMNddd:   +:       -o+s:   :s  dMMMmy+   .mMMMMMMMN` o- ```     .-`  y
            ./++dy+-.ys:dMdNMNmN/yso/ .Nm:+++: `  -hNms``mM.-hdNMmd:  `   -sNMh       +:          .    :o `mdhy::`    -mmMMMMMN. y`              y
              `:-   :odMmNhyyyydNmMNy..NN/ooo/.dmms .dMs`mMo++`hM: .ymN:.dNhsMd       o:          `    :o  y:      `/: -ohNmydd  y`  .-:::::-.  `y
                   `/yhsdMNMMoNMMNo+` `NN`hmmm:  oy`omh- shyys`:Nh `:+N. -. .md`      o/        ./y`   /o  -s     :+.-o. .s.`y/  /: yo/:----:+o :s
                      sMhsmMMNNdyss+`  -++o/-..  `:- ...o  .+  `-. `` ..-+.+ ..`      +s   :////-`.:   s+   :s:   :o:/o.   :s:   `y-:/.`   `-+..h`
                      -+oyysssshm/       oN`ohys/oo/-yo-d+o.s  :N+:hys/ysy.o+ddd.     `hy+/:::::::////sh.    `:o+/-.-..-//+-      `oso+/////-:oy.
                          `/.            ``                                             `.----------..`          `.----.`            ./oooooo/`
                                                                                                       `-.--...-``-``...:.- .-..`..:-...:.-...:.-
                                                                                                        `````-`.``` ``````.``````  `````````````.
-->

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                検索 <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                {{ Html::linkRoute('search.music.index', '楽曲検索', [], ['class'=>'dropdown-item']) }}
                                {{ Html::linkRoute('search.artist.index', 'アーティスト検索', [], ['class'=>'dropdown-item']) }}
                            </div>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    管理 <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    {{ Html::linkRoute('manage.album.index', 'アルバム管理', [], ['class'=>'dropdown-item']) }}
                                    {{ Html::linkRoute('manage.artist.index', 'アーティスト管理', [], ['class'=>'dropdown-item']) }}
                                    {{ Html::linkRoute('manage.bulk.regist.index', '一括登録', [], ['class'=>'dropdown-item']) }}
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
