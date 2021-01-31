<script>
    $(document).ready(function(){ 
    $('#like-btn').addClass('fa fa-thumbs-up');
     function loadComment(){
        var postId = $('#post_id').val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
            type:'POST',
            url:"{{ route('posts.loadComment') }}",
            data:{postId:postId,_token:_token},
            success:function(response) {
              $('#comment').val('');
              len = response['comments'].length;
              var box = '';
              for(var i=0; i<len; i++){
                  var id = response['comments'][i]['id'];
                  var comment = response['comments'][i]['comment'];
                  var user = response['comments'][i]['user'];
                  var time = response['comments'][i]['time'];
                  box += '<div class="commented-section mt-2">';
                  box += '<div class="d-flex flex-row align-items-center commented-user">';
                  box += '<h5 class="mr-2">'+user+'</h5><span class="dot mb-1">';
                  box += '</span><span class="mb-1 ml-2 time" data-time="2019-12-25 00:00:00">'+time+'</span>';
                  box += '</div><div class="comment-text-sm"><span>'+comment+'</span></div>';
                  box += '<div class="reply-section">';
                  box += '<div class="d-flex flex-row align-items-center voting-icons">';
                  box += '<i class="fa fa-sort-up fa-2x mt-3 hit-voting"></i>';
                  box += '<i class="fa fa-sort-down fa-2x mb-3 hit-voting"></i>';
                  box += '<span class="ml-2">15</span><span class="dot ml-2"></span>';
                  box += '<h6 class="ml-2 mt-1" class="reply-btn">Reply</h6>';
                  box += '</div></div></div><div class="d-flex flex-row"><input id="reply'+id+'" type="text" class="form-control form-control-sm w-50 reply-text" placeholder="Add Reply">';
                  box +=  '<button class="btn btn-primary btn-sm ml-2 send-reply-btn" type="button" id="send-reply-btn" data-id="'+id+'">Reply</button>';
                  box +=  '</div>';
                  box += '<div class="reply-section mt-2">';
                 
                  box += '</div>';
            }
              $('#comment-box').html(box);
              $('#totalComments').text(response.totalComments +' Comments');            
              $('#totalLikes').text(response.totalLikes +' Likes');            
             // swal("Thank You!", "You clicked the button!", "success");
            }
         });
     }  
     loadComment();              
      $('#comment-btn').click(function(e){
        e.preventDefault();
        var postId = $('#post_id').val();
        var comment = $('#comment').val();
        var _token = '{{ csrf_token() }}';
        $.ajax({
          type:'POST',
          url:"{{ route('posts.comment') }}",
          data:{postId:postId,comment:comment,_token:_token},
          success:function(response) {
         //   $('#comment').val('');
            loadComment();
           // $('#comment-box').html(box);            
            swal("Thank You!", "You clicked the button!", "success");
          }
       });
      });
      
      $('#like-btn').click(function(e){
        e.preventDefault();
        var btn = $(this);
        btn.toggleClass('fa fa-thumbs-up');
        var postId = $('#post_id').val();
        var _token = '{{ csrf_token() }}';
        var actionType = $(this).data('type');
        $.ajax({
          type:'POST',
          url:"{{ route('posts.like') }}",
          data:{postId:postId,actionType:actionType,_token:_token},
          success:function(response) {
            btn.addClass('fa fa-thumbs-down');
            $('#totalLikes').text(response +' Liked');
            btn.data('type','dislike');           
       }
    });
});
$(document).on('click','#send-reply-btn',function(){
    var i = $(this).data('id');
    var reply = $('#reply'+i).val();
    var _token = '{{ csrf_token() }}';
    $.ajax({
        type:'POST',
        url:"{{ route('posts.comment.reply') }}",
        data:{commentId:i,reply:reply,_token:_token},
        success:function(response) {
        $('#reply'+i).val(''); 
       // swal("Thank You!", "You clicked the button!", "success");      
     }
  });
    
});
});
  </script>