@extends('layouts.app')
@section('content')
<div class="container mt-1 mb-1">
    <div class="d-flex justify-content-center row">
        <div class="d-flex flex-column col-md-10">
            <div class="d-flex flex-row align-items-center text-left comment-top p-2 bg-white border-bottom px-4">
                <div class="profile-image"><img class="rounded-circle" src="https://i.imgur.com/t9toMAQ.jpg" width="70"></div>
                <div class="d-flex flex-column-reverse flex-grow-0 align-items-center votings ml-1"><i class="fa fa-sort-up fa-2x hit-voting"></i><span>127</span><i class="fa fa-sort-down fa-2x hit-voting"></i></div>
                <div class="d-flex flex-column ml-3">
                    <div class="d-flex flex-row post-title">
                        <h5>{{ $post->user->name }}</h5><span class="ml-2">(Jesshead)</span>
                    </div>
                    <div class="d-flex flex-row align-items-center align-content-center post-title">
                    <span class="mr-2 comments" id="totalComments">{{ $post->comments->count() }} comments&nbsp;</span>
                    <a href="#" id="like-btn" data-type=''><span class="mr-2 likes"><i class="" aria-hidden="true"></i></span></a>
                    <span class="mr-2 likes" id="totalLikes" data-likes="{{ $post->like }}">{{ $post->like }} Likes</span><span class="mr-2 dot"></span>
                    <span>{{ $post->created_at->diffForHumans() }}</span></div>
                </div>
        
            </div>
            <div class="coment-bottom bg-white p-2 px-4">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4"><img class="img-fluid img-responsive rounded-circle mr-2" src="https://i.imgur.com/qdiP4DB.jpg" width="38">
                    <input type="hidden" id="post_id" value="{{ $post->id }}">
                    <input type="text" class="form-control mr-3" id="comment" placeholder="Add comment"><button class="btn btn-primary" type="button" id="comment-btn">Comment</button></div>
                    {{--  Other User Comment  --}}
                      <div class="comment-box"></div>
                      @foreach($post->comments as $comment)
                    <div class="commented-section mt-2">
                    <div class="d-flex flex-row align-items-center commented-user">
                        <h5 class="mr-2">{{ $comment->user->name }}</h5><span class="dot mb-1"></span><span class="mb-1 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                        <span><a href="{{ url('post/comment/remove/'.$comment->id) }}" class="ml-2">{{  Auth::user()->id == $comment->user_id ? ' Remove' : ''}}</a></a></span>
                    </div>
                    <div class="comment-text-sm"><span>{{ $comment->comment }}.</span></div>
                    <div class="reply-section">
                        <div class="d-flex flex-row align-items-center voting-icons"><i class="fa fa-sort-up fa-2x mt-3 hit-voting"></i>
                        <i class="fa fa-sort-down fa-2x mb-3 hit-voting"></i><span class="ml-2">{{ $comment->replies->count() }}</span><span class="dot ml-2"></span>
                              <h6 class="ml-2 mt-1"><a href="#" class="reply-link" class="nav-link" data-id="{{ $comment->id }}"> Reply </a></h6>
                        </div>
                         <div class="d-flex flex-row" id="reply-box{{ $comment->id }}">
                        </div>
                        <div class="reply-list ml-5" style="width:250px;">
                          <div class="newReply{{ $comment->id }}">
                          </div>
                          @foreach($comment->replies as $reply)
                          <div class="commented-section mt-2 bg-light rounded" style="font-size:10px" id="replybox{{ $reply->id }}">
                          <div class="d-flex flex-row align-items-center commented-user">
                          <b class="m-1 text-danger">{{ $reply->user->name }}</b><span class="dot mb-1"></span>
                          <span class="mb-1 ml-2">{{ $reply->created_at->diffForHumans() }}</span><span>  
                            <a href="#" class="text-decoration-none ml-2 reply-remove-btn" data-id="{{ $reply->id }}">{{ Auth::user()->id == $reply->user_id ? 'Remove' : ''}}</a></span>
                          </div>
                           <div class="comment-text-sm text-info ml-3"><span>{{ $reply->reply}}</span></div>
                        </div>
                          @endforeach
                        </>
                </div>
              @endforeach               
        </div>
    </div>
</div>
@php $date = date('d-m-y') @endphp
<script>
$(document).ready(function(){ 
 $('#like-btn').addClass('fa fa-thumbs-up');  
 $('#like-btn').data('type','like');
 $('#comment-btn').click(function(e){
        e.preventDefault();
        var postId = $('#post_id').val();
        var comment = $('#comment').val();
        var _token = '{{ csrf_token() }}';
          var box = '';                 
        $.ajax({
          type:'POST',
          url:"{{ route('posts.comment') }}",
          data:{postId:postId,comment:comment,_token:_token},
          success:function(response) {
          $('#comment').val('');
          $('#totalComments').text(response.totalComments+' comments');

          box += '<div class="commented-section mt-2">';
            box += '<div class="d-flex flex-row align-items-center commented-user">';
            box += '<h5 class="">{{ Auth::user()->name  ? 'You' : ''}}</h5><span class="dot mb-1">';
            box += '</span><span class="mb-1 ml-2 time">Just Now</span>';
            box += '</div><div class="comment-text-sm"><span>'+comment+'</span></div>';
            box += '<div class="reply-section">';
            box += '<div class="d-flex flex-row align-items-center voting-icons">';
            box += '<i class="fa fa-sort-up fa-2x mt-3 hit-voting"></i>';
            box += '<i class="fa fa-sort-down fa-2x mb-3 hit-voting"></i>';
            box += '<span class="ml-2">1</span><span class="dot ml-2"></span>';
            box += '<h6 class="ml-2 mt-1"><a href="#" class="reply-link" class="nav-link" data-id="'+response.lastId+'"> Reply </a></h6></div>';              
            box += '<div class="d-flex flex-row reply-box" id="reply-box'+response.lastId+'">';  
            box +=  '</div><div class="ml-5 newReply'+response.lastId+'" style="width:250px;"></div>';

            $('.comment-box').prepend(box);
         }
       });
      });
  var boxId = ''; 
  $(document).on('click','.reply-link',function(e){
    e.preventDefault();
    boxId = $(this).data('id');
    $('#reply-box'+boxId).html('<input id="reply" type="text" class="form-control form-control-sm w-50 reply-text" placeholder="Add Reply"><button class="btn btn-primary btn-sm ml-2 send-reply-btn" type="button" id="send-reply-btn">Reply</button>');
  });

  

  $(document).on('click','#send-reply-btn',function(){
    var reply = $('#reply');
    var _token = '{{ csrf_token() }}';
    var i = boxId;
   var newReply = '';
   $('#reply-box'+i).html('');
  
    $.ajax({
        type:'POST',
        url:"{{ route('posts.comment.reply') }}",
        data:{commentId:i,reply:reply.val(),_token:_token},
        success:function(response) {
          $('#reply').val('');

          newReply += '<div class="commented-section mt-2 bg-light rounded" style="font-size:10px" id="replybox'+response.replyId+'">';
            newReply += '<div class="d-flex flex-row align-items-center commented-user">';
            newReply += '<h6 class="m-2 text-danger">You</h5>';
            newReply += '<span class="dot mb-1"></span>';
            newReply += '<span class="mb-1 ml-2">Just Now</span>';
            newReply += '<a href="#" class="text-decoration-none ml-2 new-reply-remove-btn" data-id="'+response.replyId+'">Remove</a>';
            newReply += '</div><div class="comment-text-sm text-info ml-3"><span>'+reply.val()+'</span></div>';
            
          $('.newReply'+i).prepend(newReply);
          swal("Thank You!", "You clicked the button!", "success");      
     }
  }); 
});

$('#like-btn').click(function(e){
  var like = $('#totalLikes').data('likes');
  e.preventDefault();
  if($(this).data('type') == 'like'){
    $(this).removeClass('fa fa-thumbs-up');
    $(this).addClass('fa fa-thumbs-down');
    $(this).data('type','dislike');
    $('#totalLikes').data('likes',like+1);
    $('#totalLikes').html(like+1+' Likes');
  }else{
    $(this).removeClass('fa fa-thumbs-down');
    $(this).addClass('fa fa-thumbs-up');
    $(this).data('type','like');
    $('#totalLikes').data('likes',like-1);
    $('#totalLikes').html(like-1 +' Likes');
  } 
  var likes = $('#totalLikes').data('likes'); 
  var postId = $('#post_id').val();
  var _token = '{{ csrf_token() }}';
  var actionType = $(this).data('type');
  $.ajax({
    type:'POST',
    url:"{{ route('posts.like') }}",
    data:{postId:postId,like:likes,_token:_token},
    success:function(response) {
    swal("Thank You!", "You clicked the button!", "success");             
 }
});
});

$(document).on('click','.reply-remove-btn',function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var _token = '{{ csrf_token() }}';
    $.ajax({
      type:'POST',
      url:"{{ route('posts.reply.delete') }}",
      data:{replyId:id,_token:_token},
      success:function(response) {
      $('#replybox'+id).hide();
   }
  });   
});

$(document).on('click','.new-reply-remove-btn',function(e){
  e.preventDefault();
  var id = $(this).data('id');
  var _token = '{{ csrf_token() }}';
  $.ajax({
    type:'POST',
    url:"{{ route('posts.reply.delete') }}",
    data:{replyId:id,_token:_token},
    success:function(response) {
    $('#replybox'+id).hide();
 }
});   
});
});
    </script>
@endsection
