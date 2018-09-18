$(document).ready(function(){
    var Tag = (function(){

        var tagButton = $("#tagButton");

        var init = function(){

            getTags();
            getCategories();

            tagButton.on('click', function() {
                onClickTag('text');
            })

        };

        var getTags = function(){

            $.ajax({
                url: "/tags",
                context: document.body,
                success: function(data){
                    console.log(data);
                }
            });
        };

        var getCategories = function(){

            $.ajax({
                url: "/categories",
                context: document.body,
                success: function(data){
                    console.log(data);
                }
            });
        };

        var onClickTag = function(some){

            console.log(some);
        };

        return {
            init : init
        }

    })();

   Tag.init();

});
