@component('emails.layout.html.main')
	@component('emails.layout.html.main.header')
		Your message could not be translated.
	@endcomponent
	@component('emails.layout.html.main.body')
		We're sorry to say we could not translate the following message due to a system error.
		<br>
		<br>
		<strong>"{{ $message->body  }}"</strong>
		<br>
		<br>
		We have already been notified and will be working as fast as we can to fix the issue. Please try again later or get in touch for an update.
		<br>
		<br>
		Customer Support,
		<br>
		bemail
	@endcomponent
	@include('emails.layout.html.message-thread')
@endcomponent
