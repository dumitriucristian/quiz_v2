$(document).ready(function(){
    var Category = (function(){

       var init = function(){
           console.log('tree');
         getTree();
       };

       var htmlData='';

       var addLi = function(value,lvl){
           return "<li class='ml-"+lvl+"'><a href='#'>"+value.name+"</a></li>";
       }

       var addChildren = function(value,lvl, html =''){
            lvl++;
           if('childrens' in value){
               for(var i = 0; i < value.childrens.length; i++){
                   html += addLi(value.childrens[i], lvl)

                   addChildren(value.childrens[i],lvl,html);
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
                      html += '<li class="ml-'+lvl+'"><a href="#" >'+value.name+'</a>';
                      if('childrens' in value){
                         html += addChildren(value, lvl);
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
