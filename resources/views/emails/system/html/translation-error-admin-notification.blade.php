<h2>System Translation Error on {{ $messageWithError->error->created_at->format('Y/m/d H:i') }}</h2>
<br>
<h4>Message</h4>
{{ $messageWithError->hash }}
<br>
<h4>Code</h4>
{{ $messageWithError->error->code }}
<br>
<h4>Description</h4>
{{ $messageWithError->error->msg }}
<br>
<h4>Sender</h4>
{{ $messageWithError->sender_email }}