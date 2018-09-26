$(document).ready(function(){
    var Category = (function(){
       var quizId = "este";
       var init = function(){
            getTree();

       };

       var addLi = function(value){
           return "<li class='list-inline'>" +
                        "<a href='#' class='list-group-item list-group-item-action addCat' data-cat-id="+value.id+">"+value.name+"</a>" +
               "</li>";
       };

       var addChildren = function(value, html =''){

           if('childrens' in value){
               for(var i = 0; i < value.childrens.length; i++){
                   html += addLi(value.childrens[i])
                   addChildren(value.childrens[i],html);
               }
           }
           return html;
       };

        var getTree = function(){
            var treeHtml ='';
            var questionId = '';
            var categoryId = '';

            $.ajax({
                url: "/categoryTree/",
                context: document.body,
                success: function(data){
                    var html ='<h6>Categories</h6>';
                    //getChildren(data);
                    $.each(JSON.parse(data),function(key, value){
                        console.log(value);
                        var lvl = 0;
                        html += '<li class="list-inline">' +
                            '<a href="#" data-cat-id="'+value.id+'" ' +
                            'class="list-group-item list-group-item-action pl-1 addCat">'+value.name+
                            '</a>';
                        if('childrens' in value){
                            html += '<ul class="list-group">'+addChildren(value)+'</ul>';
                        }
                        html += "</li>";
                        treeHtml = html;
                    });

                    $(".modal-body").empty().append(treeHtml);

                    $('body').on('click', '.categoryButton',  function(){
                        questionId = $(this).attr('data-question');
                        $(".modal-body").empty().append(treeHtml);
                    });

                    $('body').on('click', '.addCat',  function(){
                        categoryId = $(this).attr('data-cat-id') ;
                        addCategoryToQuestion(categoryId, questionId);
                    });
                }
            });
        };

        var addCategoryToQuestion = function(categoryId, questionId) {
            $.ajax({
                url: "/addQuestionCategory/" + questionId + "/" + categoryId,
                context: document.body,
                success: function (data) {
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
