web.php
--------
```
Route::get('contact', 'ContactController@create');
Route::get('contact', 'ContactController@store');
```

ContactController.php
--------------------
```
php artisan make:controller ContactController
```
```

use Illuminate\Support\Facades\Mail;

public function create()
{
  return view('contact.create');
}
public function store()
{
  $data = request()->valudate([
    'name' => 'required',
    'email' => 'required|email',
    'message' => 'required',
  ]);
  
  Mail::to('test@test.com')->send(new ContactMail($data));
  
  Return redirect('contact')->with('message', 'Thanks for your message, We will be in touch.');
  
}
```

contact\create.blade.php
----------------------------
```
@if( ! session()->has('message'))
        <form action="/contact" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                <div>{{ $errors->first('name') }}</div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                <div>{{ $errors->first('email') }}</div>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" cols="30" rows="10"
                          class="form-control">{{ old('message') }}</textarea>
                <div>{{ $errors->first('message') }}</div>
            </div>

            @csrf

            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    @endif
```
layouts\app.blade.php
---------------------
```
@if(session()->has('message'))
  <div class="alert alert-success" role="alert">
    <strong>Success</strong> {{ session()->get('message') }}
  </div
@endif
```

Craete Mailable Class
------------
```
php artisan make:mail ContactMail --markdown=emails.contact.contact-from
```
ContactMail.php
---------------
```
class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact.contact-from');
    }
}

```

.env
--------
```
MAIL_USERNAME= your username
MAIL_PASSWORD= your password
```

emails\contact\contact-from.blade.php
-------------------------------------
```
@component('mail::message')

#Thank you for your message

<strong>Name</strong> {{ $data['name'] }}
<strong>Email</strong> {{ $data['email'] }}
<strong>Message</strong> {{ $data['message'] }}

@endcomponent
```
