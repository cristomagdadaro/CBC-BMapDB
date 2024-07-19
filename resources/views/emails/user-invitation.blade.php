@component('mail::message')
{{ __('Hi :name, you have been invited to join the pioneering users of the Breeder\'s Map Database System!', ['team' => 'DA-Crop Biotechnology Center', 'name' => $name]) }}

@component('mail::panel')
    <img src="{{ asset('img/logo_no_bg.png') }}" alt="Logo" style="max-width: 100%; height: auto;">
@endcomponent

{{ __('We are pleased to introduce the Breeder\'s Map Database System, a web-based application designed to store and manage breeding information for key commodities in the Philippines, especially those involving biotechnology.') }}

{{ __('This system is a valuable tool for researchers, allowing them to access, monitor, and share information about current research and researchers. By making information exchange more efficient, the Breeder\'s Map Database System aims to enhance collaboration and progress in the field.') }}

{{ __('We believe this platform will be a useful resource for researchers and stakeholders, helping to build a more connected and informed research community.') }}

{{ __('If you do not have an account, you may create one by clicking the button below. After creating an account, you may may log in your credentials and start exploring the features the system offer:') }}

@component('mail::button', ['url' => route('register')])
{{ __('Create Account') }}
@endcomponent

{{ __('If you did not expect to receive an invitation from us, you may discard this email.') }}
@endcomponent
