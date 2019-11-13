@if(session()->has('message'))
  <div class="alert alert-success" role="alert">
    <strong>Success</strong> {{ session()->get('message') }}
  </div
@endif
