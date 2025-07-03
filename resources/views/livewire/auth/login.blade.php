<div>
    <div class="container" style="margin-top: 120px">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 rounded shadow">
                    <div class="card-body">
                        <h5 class="text-center"> <i class="fa fa-user-circle"></i> LOGIN</h5>
                        <hr>
                        @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif
                        <form wire:submit.prevent="login">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">ALAMAT EMAIL</label>
                                <input type="text" wire:model.lazy="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Alamat Email">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold">PASSWORD</label>
                                <input type="password" wire:model.lazy="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <p>
                                <a href="{{url('/register')}}"
                                    class="mt-4 text-decoration-none">
                                    Belum ada akun ? Silahkan register
                                </a>
                            </p>

                            <button type="submit" class="btn btn-primary btn-block">LOGIN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>