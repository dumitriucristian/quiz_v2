<!DOCTYPE html>
<html>
<head></head>
<body>
<passport-clients></passport-clients>
<passport-authorized-clients></passport-authorized-clients>
<passport-personal-access-tokens></passport-personal-access-tokens>

<div id="ss" >
    <p>@{{message}}</p>
    <p>@{{secondText}}</p>
    <button v-on:click="doClick">test @{{counter}}</button>
    <p>The button above has been clicked @{{counter}} times.ssss</p>
</div>

<script src="{{asset('js/app.js')}}"></script>
</body>
</html>ss