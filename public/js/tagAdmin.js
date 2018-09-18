$(document).ready(function(){
    var Tag = (function(){

        var tagButton = $("#tagButton");
        var questionId = tagButton.attr('data-question');
        var modalTitle = $(".modal-title");
        var modalBody = $(".modal-body");

        var init = function(){

            getTagsHtml();

            tagButton.on('click', function() {
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
             elem.removeClass('addTag');
             elem.addClass('removeTag');
             elem.html( elem.html() + ' X');
             $('#removeTagsContainer').append(elem);
        }

        var removeTag =  function(elem){
            elem.removeClass('removeTag');
            elem.addClass('addTag');
            html = (elem.html().slice(0,elem.html().length - 2));
            elem.html( html );
            $('#addTagsContainer').append(elem);
        }

        var getTagsHtml = function(){
            $.ajax({
                url: "/questionTags/"+questionId,
                context: document.body,
                success: function(data){
                   var availableTagHtml = getAvailableTag(data.data.missingQuestionTags);
                   var questionTagsHtml = getQuestionTags(data.data.questionTags);
                   modalBody.append(questionTagsHtml);
                   modalBody.append(availableTagHtml);
                }
            });
        };

        var getAvailableTag = function(tags){

            var tagsHtml = '<div id="addTagsContainer" class="ml-4 m-2 p-2 border border-primary col-11"><h5>Add tags</h5>';

            $.each(tags, function( key, value){
                tagsHtml+='<button type="button" data-tag-id='+value.id+' class="addTag btn btn-info btn-sm mb-1 ml-1">'+value.name+'</button>';
            });

            tagsHtml +='</div>';

            return tagsHtml;

        };

        var getQuestionTags = function(tags){

            var tagsHtml = '<div id="removeTagsContainer" class="ml-4 m-2 p-2 border border-primary col-11">' +
                '<h5>Remove tags</h5>';

            $.each(tags, function( key, value){
                tagsHtml+='<button type="button" data-tag-id='+value.id+' class="removeTag btn btn-info btn-sm mb-1 ml-1">'+value.name+' X</button>';
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
