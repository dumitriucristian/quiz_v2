<!DOCTYPE html>
<head></head>
<body>
<div id="ss" >
    <p>@{{message}}</p>
    <p>@{{secondText}}</p>
    <button v-on:click="doClick">test @{{counter}}</button>
    <p>The button above has been clicked @{{counter}} times.</p>
</div>

<script src="{{asset('js/app.js')}}"></script>
</body>
</html>