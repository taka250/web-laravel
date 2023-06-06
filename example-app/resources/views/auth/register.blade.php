

<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />

                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />

                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4 flex">
                <x-input-label for="email" :value="__('Email')" />

                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />

                <button id="get-code" type="button" class="ml-2 px-4 py-2 border border-gray-300 bg-white text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none">
    获取验证码
</button>

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Verification Code -->
            <div class="mt-4">
                <x-input-label for="verification-code" :value="__('Verification Code')" />

                <x-text-input id="verification-code" class="block mt-1 w-full" type="text" name="verification_code" required />

                <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

<script>
let countdown = 60;
let timerId;
let firstClick = true;

// Get the button
const btnGetVerificationCode = document.getElementById('get-code');

btnGetVerificationCode.addEventListener('click', function () {
    const emailInput = document.getElementById('email');
    const email = emailInput.value;
    if (countdown < 60 && countdown > 0) {
        alert(`请等待 ${countdown} 秒后再试`);
    } else if (email) {
        // Disable the button before sending the request
        this.setAttribute('disabled', 'true');
        fetch(`http://127.0.0.1:8000/mail?email=${email}`)
            .then((response) => {
                if (response.ok && firstClick) {
                    firstClick = false;
                    alert('发送成功');
                    return response.json();
                } else {
                    throw new Error('发送失败');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('获取验证码失败，请重试');
            });

        // Start the countdown
        countdown = 60;
        timerId = setInterval(function () {
            countdown--;
            if (countdown <= 0) {
                clearInterval(timerId);
                btnGetVerificationCode.removeAttribute('disabled');
                btnGetVerificationCode.innerText = '获取验证码';
                countdown = 60;
                firstClick = true;
            } else {
                btnGetVerificationCode.innerText = `重新获取验证码（${countdown}s）`;
            }
        }, 1000);
    } else {
        alert('请输入电子邮件地址');
    }
});


</script>
