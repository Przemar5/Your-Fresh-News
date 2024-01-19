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
                    @foreach(\App\Category::childrenOf('topics') as $subCategory)
                    <a class="dropdown-item" href="{{ route('categories.show', ['slug' => $subCategory->slug]) }}">
                        {{ $subCategory->name }}
                    </a>
                    @endforeach
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPlaces" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Places
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownPlaces">
                    @foreach(\App\Category::childrenOf('places') as $subCategory)
                    <a class="dropdown-item" href="{{ route('categories.show', ['slug' => $subCategory->slug]) }}">
                        {{ $subCategory->name }}
                    </a>
                    @endforeach
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