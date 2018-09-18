$(document).ready(function(){
    var tag = (function(){
        var get = function(){
            console.log('test');
        };

        return {
            test : test
        }
    })();
   Tag.test();

});
