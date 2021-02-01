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
                    <span class="mr-2 comments" id="totalComments"> comments&nbsp;</span>
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
                    </div>
                    <div class="comment-text-sm"><span>{{ $comment->comment }}.</span></div>
                    <div class="reply-section">
                        <div class="d-flex flex-row align-items-center voting-icons"><i class="fa fa-sort-up fa-2x mt-3 hit-voting"></i>
                        <i class="fa fa-sort-down fa-2x mb-3 hit-voting"></i><span class="ml-2">{{ $comment->replies->count() }}</span><span class="dot ml-2"></span>
                              <h6 class="ml-2 mt-1"><a href="#" class="reply-link" class="nav-link" data-id="{{ $comment->id }}"> Reply </a></h6>
                        </div>
                         <div class="d-flex flex-row" id="reply-box{{ $comment->id }}">
                        </div>
                        <div class="reply-list">
                          <div class="newReply{{ $comment->id }}">
                          </div>
                          @foreach($comment->replies as $reply)
                          <div class="commented-section mt-2" style="font-size:10px">
                          <div class="d-flex flex-row align-items-center commented-user">
                          <h6 class="mr-2 text-danger">{{ $reply->user->name }}</h5><span class="dot mb-1"></span>
                          <span class="mb-1 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                          </div>
                           <div class="comment-text-sm text-info"><span>{{ $reply->reply}}</span></div>
                        </div>
                         <hr>
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
           box += '<div class="commented-section mt-2">';
                  box += '<div class="d-flex flex-row align-items-center commented-user">';
                  box += '<h5 class="mr-2">{{ Auth::user()->name  ? 'You' : ''}}</h5><span class="dot mb-1">';
                  box += '</span><span class="mb-1 ml-2 time">Just Now</span>';
                  box += '</div><div class="comment-text-sm"><span>'+comment+'</span></div>';
                  box += '<div class="reply-section">';
                  box += '<div class="d-flex flex-row align-items-center voting-icons">';
                  box += '<i class="fa fa-sort-up fa-2x mt-3 hit-voting"></i>';
                  box += '<i class="fa fa-sort-down fa-2x mb-3 hit-voting"></i>';
                  box += '<span class="ml-2">15</span><span class="dot ml-2"></span>';               
                  box +=  '</div>';  
        $.ajax({
          type:'POST',
          url:"{{ route('posts.comment') }}",
          data:{postId:postId,comment:comment,_token:_token},
          success:function(response) {
          $('#comment').val('');
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
   newReply += '<div class="commented-section mt-2" style="font-size:10px">';
   newReply += '<div class="d-flex flex-row align-items-center commented-user">';
   newReply += '<h6 class="mr-2 text-danger">You</h5>';
   newReply += '<span class="dot mb-1"></span>';
   newReply += '<span class="mb-1 ml-2">Just Now</span></div>';
   newReply += '<div class="comment-text-sm text-info"><span>'+reply.val()+'</span></div>';
    $.ajax({
        type:'POST',
        url:"{{ route('posts.comment.reply') }}",
        data:{commentId:i,reply:reply.val(),_token:_token},
        success:function(response) {
          $('#reply').val('');
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
});
    </script>
@endsection
