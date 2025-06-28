<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dewakoding Kasir</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&display=swap">

  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }
  </style>

</head>

<body style="background-color: #f0f8ff">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">DEWAKODING-KASIR</a>
        <div class="d-flex align-items-center">
          @auth
          <strong class="mx-4">{{ Auth::user()->name }}</strong>
          @endauth
          @livewire('auth.logout')
        </div>
      </div>
    </nav>

    @auth
    <a href="{{url('/')}}" type="button" class="btn btn-light mt-4 {{ request()->is('pos*', '') || request()->is('/') ? 'active' : '' }}">Tampilan POS</a>
    <!-- <a href="{{url('/product')}}" type="button" class="btn btn-light mt-4 {{ request()->is('product*') ? 'active' : '' }}">Produk</a> -->
    <a href="{{url('/order')}}" type="button" class="btn btn-light mt-4 {{ request()->is('order*') ? 'active' : '' }}">Daftar Order</a>
    @endauth

    {{ $slot }}
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>