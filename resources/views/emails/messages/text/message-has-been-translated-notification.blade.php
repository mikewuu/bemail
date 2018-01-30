@component('emails.messages.text.partials.layout')

	Message: {{ $translatedMessage->hash }}
	Your message has been translated and sent.

	Below, we have included your message along with the translation as your recipients will see it. Thank you for using our services and if you have any suggestions or questions, feel free to reply directly to this email.

	@include('emails.messages.text.partials.thread')
@endcomponent