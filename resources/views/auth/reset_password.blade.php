<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/output.css">
    <title>Reset Password</title>
</head>
<body>
    <div class="flex py-10 md:py-20 px-5 md:px-32 bg-gray-200 min-h-screen">
        <div class="flex shadow w-full flex-col-reverse lg:flex-row">
            <div class="w-full lg:w-1/2 bg-white p-10 px-5 md:px-20">
                <h1 class="font-bold text-xl text-gray-700">Reset Password</h1>
                <p class="text-gray-600">Enter your new password.</p>
                <br>
                @if (Session::has('status'))
                    <p class="text-green-600">{{ Session::get('status') }}</p>
                @endif
                @if (Session::has('errors'))
                    <ul>
                        @foreach (Session::get('errors') as $error)
                            <li style="color: red">{{ $error[0] }}</li>
                        @endforeach
                    </ul>
                @endif
                <form action="{{ route('password.update')}}" class="mt-10" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ request()->email }}">
                    <div class="my-3">
                        <label class="font-semibold" for="password">New Password</label>
                        <input required type="password" placeholder="New password" name="password" id="password" class="block border-2 rounded-full mt-2 py-2 px-5 w-full">
                    </div>
                    <div class="my-3">
                        <label class="font-semibold" for="password_confirmation">Confirm New Password</label>
                        <input required type="password" placeholder="Confirm new password" name="password_confirmation" id="password_confirmation" class="block border-2 rounded-full mt-2 py-2 px-5 w-full">
                    </div>
                    <div class="my-5">
                        <button type="submit" class="w-full rounded-full bg-blue-400 hover:bg-blue-600 text-white py-2">RESET PASSWORD</button>
                    </div>
                </form>
                <span>Remember your password? <a href="/login_member" class="text-blue-400 hover:text-blue-600">Login here.</a></span>
            </div>
            <div class="w-full lg:w-1/2 bg-blue-400 flex justify-center items-center">
                <img src="/uploads/vayya2.jpeg" alt="Login Image" class="w-full h-auto">
            </div>
        </div>
    </div>
    <script src="/sbadmin2/vendor/jquery/jquery.min.js"></script>
</body>