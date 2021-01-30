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
                    <div class="d-flex flex-row align-items-center align-content-center post-title"><span class="bdge mr-1">video</span><span class="mr-2 comments">{{ $post->comments->count() }} comments&nbsp;</span><span class="mr-2 dot"></span><span>{{ $post->created_at->diffForHumans() }}</span></div>
                </div>
            </div>
            <div class="coment-bottom bg-white p-2 px-4">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4"><img class="img-fluid img-responsive rounded-circle mr-2" src="https://i.imgur.com/qdiP4DB.jpg" width="38">
                    <input type="hidden" id="post_id" value="{{ $post->id }}">
                    <input type="text" class="form-control mr-3" id="comment" placeholder="Add comment"><button class="btn btn-primary" type="button" id="comment-btn">Comment</button></div>
                    {{--  Other User Comment  --}}
            <div id="comment-box">
            </div>        
                
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){              
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
            $('#comment').val('');
            len = response['comments'].length;
            var box = '';
            for(var i=0; i<len; i++){
                var comment = response['comments'][i].comment;
                var created_at = response['comments'][i].created_at;
                box += '<div class="commented-section mt-2">';
                box += '<div class="d-flex flex-row align-items-center commented-user">';
                box += '<h5 class="mr-2">{{ Auth::user()->name }}</h5><span class="dot mb-1">';
                box += '</span><span class="mb-1 ml-2 time" data-time="2019-12-25 00:00:00">5 hourse Ago</span>';
                box += '</div><div class="comment-text-sm"><span>'+comment+'</span></div>';
                box += '<div class="reply-section">';
                box += '<div class="d-flex flex-row align-items-center voting-icons">';
                box += '<i class="fa fa-sort-up fa-2x mt-3 hit-voting"></i>';
                box += '<i class="fa fa-sort-down fa-2x mb-3 hit-voting"></i>';
                box += '<span class="ml-2">15</span><span class="dot ml-2"></span>';
                box += '<h6 class="ml-2 mt-1">Reply</h6></div></div></div>';
            }
            $('#comment-box').html(box);            
            swal("Thank You!", "You clicked the button!", "success");
          }
       });
      });
    });
  </script>
@endsection
