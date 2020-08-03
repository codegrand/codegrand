
// contact form ajax call

$(document).ready(function() {

	$("#contactUs .alert").hide();

	$("#contactForm").submit(function(event) {
		/* stop form from submitting normaly */
		event.preventDefault();
		var contactFormData = $("#contactForm").serialize();
		console.log(contactFormData);

		$.ajax({
			url:"sendmail.php",
			type: 'POST',
			data: contactFormData,

			success:function(response) {
				$("#funct-msg").append('Thank You, we will soon come to you');
				$("input#contact-btn").attr("disabled", "disabled");
			},
			error:function() {
				$("#funct-msg").append('something went wrong');
			}
		});
		
	});
});

