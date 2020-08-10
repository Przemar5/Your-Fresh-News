<nav class="navbar fixed-top navbar-expand-xl navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('home') }}">
        {{ config('app.name', 'Laravel') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item {{ Request::is('articles.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('articles.index') }}">
                    Fresh
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTopics" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Topics
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownTopics">
                    <a class="dropdown-item" href="http://localhost:8000/categories/politics">
                        politics
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/economy">
                        economy
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/eu">
                        EU
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/us">
                        US
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/science">
                        science
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/technology">
                        technology
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/ecology">
                        ecology
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/lgbt">
                        LGBT
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/culture">
                        culture
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/society">
                        society
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/stars">
                        stars
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/movies">
                        movies
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/music">
                        Music
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/religion">
                        Religion
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/refugees">
                        Refugees
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPlaces" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Places
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownPlaces">
                    <a class="dropdown-item" href="http://localhost:8000/categories/world">
                        world
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/europe">
                        Europe
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/north-america">
                        North America
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/asia">
                        Asia
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/asia-minor">
                        Asia Minor
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/latin-america">
                        Latin America
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/africa">
                        Africa
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/australia">
                        Australia
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/antarctica">
                        Antarctica
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/space">
                        space
                    </a>
                    <a class="dropdown-item" href="http://localhost:8000/categories/moon">
                        Moon
                    </a>
                </div>
            </li>

            <li class="nav-item {{ Request::is('contact') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('contact') }}">
                    Contact
                </a>
            </li>
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        {{ __('Login') }}
                    </a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            {{ __('Register') }}
                        </a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @if(Auth::user()->is('writer'))
                            <a class="dropdown-item" href="{{ route('articles.create') }}">
                                {{ __('Create Article') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('articles.index') }}">
                                Articles
                            </a>

                            <a class="dropdown-item" href="{{ route('categories.index') }}">
                                Categories
                            </a>

                            <a class="dropdown-item" href="{{ route('tags.index') }}">
                                Tags
                            </a>
                        
                            <div class="dropdown-divider"></div>
                        @endif

                        <a class="dropdown-item" href="{{ route('profiles.show', Auth::id()) }}">
                            {{ __('Profile') }}
                        </a>

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


        {!! Form::open([
            'route' => 'search',
            'method' => 'GET',
            'class' => 'form-inline my-2 my-lg-0',
        ]) !!}

            <input class="form-control mr-sm-2" type="search" name="phrase" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>

        {!! Form::close() !!}
    </div>
</nav>