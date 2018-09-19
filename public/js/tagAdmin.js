$(document).ready(function(){
    var Tag = (function(){

        var tagButton = $(".tagButton");
        var modalTitle = $(".modal-title");
        var modalBody = $(".modal-body");

        var init = function(){

            tagButton.on('click', function() {
                modalBody.empty();
                var questionId =$(this).attr('data-question');
                getTagsHtml(questionId);
                setModalTitle('Add and remove question tags');
                modalBody.append( `<div class="row" id="mainContainer"></div>`);
            });

            $('body').on('click', '.addTag', function(){
                   addTag($(this));
            });

            $('body').on('click', '.removeTag', function(){
                removeTag($(this));
            });
        };

        var addTag = function(elem){
             var questionId = elem.attr('data-question-id');
             var tagId = elem.attr('data-tag-id');

            $.ajax({
                url: "/admin/addQuestionTag/"+questionId+"/"+tagId,
                context: document.body,
                success: function(data){
                    console.log('done');
                }
            });

             console.log(tagId, questionId);
             elem.removeClass('addTag');
             elem.addClass('removeTag');
             elem.html( elem.html() + ' X');
             $('#removeTagsContainer').append(elem);
        };

        var removeTag =  function(elem){

            var questionId = elem.attr('data-question-id');
            var tagId = elem.attr('data-tag-id');

            $.ajax({
                url: "/admin/removeQuestionTag/"+questionId+"/"+tagId,
                context: document.body,
                success: function(data){
                    console.log('done');
                }
            });

            elem.removeClass('removeTag');
            elem.addClass('addTag');
            html = (elem.html().slice(0,elem.html().length - 2));
            elem.html( html );
            $('#addTagsContainer').append(elem);
        };

        var getTagsHtml = function(questionId){
            $.ajax({
                url: "/questionTags/"+questionId,
                context: document.body,
                success: function(data){
                   var availableTagHtml = getAvailableTag(data.data.missingQuestionTags, questionId);
                   var questionTagsHtml = getQuestionTags(data.data.questionTags, questionId);
                   modalBody.append(questionTagsHtml);
                   modalBody.append(availableTagHtml);
                }
            });
        };

        var getAvailableTag = function(tags,questionId){

            var tagsHtml = '<div id="addTagsContainer" class="ml-4 m-2 p-2 border border-primary col-11"><h5>Add tags</h5>';

            $.each(tags, function( key, value){
                tagsHtml+='<button type="button" data-question-id='+questionId+' data-tag-id='+value.id+' class="addTag btn btn-info btn-sm mb-1 ml-1">'+value.name+'</button>';
            });

            tagsHtml +='</div>';

            return tagsHtml;
        };

        var getQuestionTags = function(tags, questionId){

            var tagsHtml = '<div id="removeTagsContainer" class="ml-4 m-2 p-2 border border-primary col-11"><h5>Remove tags</h5>';

            $.each(tags, function( key, value){
                tagsHtml+='<button type="button" data-question-id='+questionId+' data-tag-id='+value.id+' class="removeTag btn btn-info btn-sm mb-1 ml-1">'+value.name+' X</button>';
            });

            tagsHtml +='</div>';

            return tagsHtml;
        };

        var setModalTitle = function(title){
            modalTitle.text(title);
        };


        return {
            init : init
        }

    })();

   Tag.init();
});
