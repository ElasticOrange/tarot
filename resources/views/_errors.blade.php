@if($errors->any())
    <div class="alert alert-danger">
        <p>
            <strong>Error</strong>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </p>
    </div>
@endif
