<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <style type="text/css" rel="stylesheet">
        .input-error {
            background-color: #fbaeac;
        }

        .input-error:focus {
            outline: solid 3px #d9534f;
        }

        .footer {
            height: 8.5rem;
            background-color: #f7f7f9;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap-theme.min.css') }}">

    @yield('links')
</head>
<body>
    @include('includes.navbar')

    <div id="app" class="mt-5 pt-5">
        <main class="py-4">
            <div class="container-fluid">
                @foreach(session()->get('alerts') ?? [] as $alert)
                    @if(is_array($alert))
                        @php($alertClass = [
                            'success' => 'success', 
                            'info' => 'info', 
                            'warning' => 'warning', 
                            'error' => 'danger'
                        ][$alert['status']])
                        <div class="alert alert-{{$alertClass}} alert-dismissible fade show text-center" role="alert">
                            {{ $alert['message'] }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                @endforeach
                {{ session()->forget('alerts') }}

                @if(!empty($success))
                <div class="alert alert-success">
                    {{ $success }}
                </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

    <footer class="footer d-flex flex-direction-column justify-content-center align-items-center mt-5 p-5">
        <div class="text-center">
            <span class="d-block mb-2">
                Przemysław Krogulski &copy; 2020
            </span>
            <span class="d-block">
                primero.el.dev@gmail.com
            </span>
        </div>
    </footer>

    <!-- Font-awesome --> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/js/all.min.js" integrity="sha512-YSdqvJoZr83hj76AIVdOcvLWYMWzy6sJyIMic2aQz5kh2bPTd9dzY3NtdeEAzPp/PhgZqr4aJObB3ym/vsItMg==" crossorigin="anonymous"></script>

    @yield('scripts')
</body>
</html>
