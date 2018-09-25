$(document).ready(function(){
    var Category = (function(){

       var init = function(){
           console.log('tree');
         getTree();
       };

       var htmlData='';

       var addLi = function(value){
           return "<li class='list-inline'><a href='#' class='list-group-item list-group-item-action'>"+value.name+"</a></li>";
       }

       var addChildren = function(value, html =''){

           if('childrens' in value){
               for(var i = 0; i < value.childrens.length; i++){
                   html += addLi(value.childrens[i])

                   addChildren(value.childrens[i],html);
               }
           }
           return html;
       } ;

       var getTree = function(){
           $.ajax({
               url: "/categoryTree/",
               context: document.body,
               success: function(data){
                   var html ='<h6>Categories</h6>';
                   //getChildren(data);
                  $.each(JSON.parse(data),function(key, value){
                        console.log(value);
                       var lvl = 0;
                      html += '<li class="list-inline"><a href="#" class="list-group-item list-group-item-action pl-1">'+value.name+'</a>';
                      if('childrens' in value){
                         html += '<ul class="list-group">'+addChildren(value)+'</ul>';
                      }

                      html += "</li>";

                  });
                  $("#tree").append(html);
                   console.log( html );

               }

           });
       }

        return {
            init : init
        }

    })();

   Category.init();
});
