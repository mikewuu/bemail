@component('emails.messages.text.partials.layout')

	Message: {{ $translatedMessage->hash }}
	We've translated your message.

	Your message has been translated and as you have selected the 'Send-to-Self' option, your message was not automatically sent to any recipients and is attached below.

	@include('emails.messages.text.partials.thread')
@endcomponent