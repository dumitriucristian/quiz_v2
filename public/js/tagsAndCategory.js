$(document).ready(function(){
    var tag = (function(){
        var test = function(){
            console.log('test');
        };

        return {
            test : test
        }
    })();
   Tag.test();
});
