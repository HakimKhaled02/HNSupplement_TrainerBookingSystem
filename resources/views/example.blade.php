@extends('layouts.app')

@section('title', 'Example Page - HN Supplement')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4" style="color: #00ff00;">Example Page</h1>
            <p class="lead" style="color: #cccccc;">
                This is an example page showing how to use the navbar and footer components.
                Simply extend the 'layouts.app' layout and your page will automatically include
                the navbar and footer.
            </p>
            <div class="alert alert-dark mt-4" style="background-color: #1a1a1a; border: 1px solid #00ff00; color: #cccccc;">
                <h5 class="alert-heading" style="color: #00ff00;">How to use:</h5>
                <pre style="color: #cccccc; background-color: #000; padding: 1rem; border-radius: 5px;">
@extends('layouts.app')

@section('title', 'Your Page Title')

@section('content')
    &lt;!-- Your content here --&gt;
@endsection
                </pre>
            </div>
        </div>
    </div>
</div>
@endsection

