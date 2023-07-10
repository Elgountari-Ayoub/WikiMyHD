<!DOCTYPE html>
<html>

<head>
    <style>
        /* Add styling for the PDF here */
    </style>
</head>

<body>

    <div style="
    display: flex;
    justify-content: space-between;
    margin-bottom: 8rem;
    ">
        <img src="https://havetdigital.fr/img/logo.png" alt="{{ config('app.name') }} Logo"
            style="width:45%;background-color: #ddd ;border-radius: 1rem;">
        <span style="margin-left: auto; position:absolute; right:0%">
            Créé à: {!! $article->created_at !!}
        </span>
    </div>

    <div class="margin-top:8rem">
        {{-- <h1>{!! $article->title !!}</h1> --}}
        {!! $article->content !!}
    </div>
</body>

</html>

{{-- {{ $content }} --}}
