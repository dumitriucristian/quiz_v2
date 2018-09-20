$(document).ready(function(){
    var Category = (function(){

       var init = function(){
           console.log('tree');
         getTree();
       };

       var getTree = function(){
           $.ajax({
               url: "/categoryTree/",
               context: document.body,
               success: function(data){
                  console.log(data);
               }
           });
       }

        return {
            init : init
        }

    })();

   Category.init();
});
