@if(session('message'))

<div class="alert alert-info" role="alert">
    <strong>
        <i class="fa-solid fa-thumbs-up"></i>
    </strong> {{session('message')}}
</div>

@endif