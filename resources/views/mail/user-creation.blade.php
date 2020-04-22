<div>
    <p>Greetings!<br><br>
        You have been registered to the 5S Portal. Click the link provided below and use the default password for access.<br><br>

        Link: <a href="{{ url('login') }}">5s-Portal</a><br>
        Default Password:<strong> {{ $default_password }}</strong><br><br>

        Reminder: change your password upon log in.<br>
        Thank you very much!<br><br>
        <img src="{{ $message->embed($pathToImage) }}"  alt="QR" title="QR" style="width="200" height=200" data-auto-embed="attachment">
    </p>
</div>