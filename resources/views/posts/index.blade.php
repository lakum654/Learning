@extends('layouts.app')

@section('content')
@php $favoriteList = Auth::user()->myList->pluck('id') @endphp   
<div class="container"> 
<div class="row">    
@foreach($posts as $post)
<div class="col-4">
  <div class="card mt-2">
    <div class="card-header bg-white font-weight-bold">{{ $post->title }}</div>
    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
      <img
        src="https://mdbootstrap.com/img/new/standard/nature/112.jpg"
        class="img-fluid"
      />
      <a href="#!">
        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15)"></div>
      </a>
    </div>
    <div class="card-body">
      <h5 class="card-title font-weight-bold">{{ $post->title }}</h5>
      <p class="card-text">
       {{ Str::limit($post->desc,50) }}
      </p>
      <a href="{{ route('posts.show',$post->id) }}" class="btn btn-primary btn-sm">Detail..</a>
      <a href="#!" class="btn-link float-right favorite-btn {{ in_array($post->id,$favoriteList->toArray()) ? 'text-danger' : ''}}" data-id="{{ $post->id }}"><i class="fa fa-star"></i></a>
    </div>
  </div>
</div>    
@endforeach
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('posts.store') }}" method="post">
      <div class="modal-body">
          @csrf
          <div class="form-group">
            <label for="title">Post Title:</label>
            <input type="text" name="title" class="form-control form-control-sm" placeholder="Post Title" id="title">
          </div>
          <div class="form-group">
            <label for="pwd">Post Description:</label>
            <textarea class="form-control form-control-sm" name="desc" placeholder="Description.." id="desc"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Publish</button>
      </div>
    </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    $(document).on('click','.favorite-btn',function(e){
      e.preventDefault();
      var selectedClass = $(this);
      var postId = $(this).data('id');
      var _token = '{{ csrf_token() }}';
      $.ajax({
        type:'POST',
        url:"{{ route('posts.favorite') }}",
        data:{postId:postId,_token:_token},
        success:function(data) {
           if(data == 1){
            selectedClass.addClass('text-danger'); 
           }
        }
     });
    });
  });
</script>
@endsection
