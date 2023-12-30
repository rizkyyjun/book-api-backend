@extends('layouts.main')

@section('container')
    {{-- <div class="row">
        <div class="col-md-4">

        </div>
    </div> --}}

    <main class="form-signin w-100 m-auto">
        <form>
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
        
            <div class="form-floating">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
        
            <button class="btn btn-primary w-100 py-2 mt-3" type="submit">Sign in</button>
        </form>
        <small>
            Not registered? <a href="/register">Register Now!</a>
        </small>
  </main>
@endsection