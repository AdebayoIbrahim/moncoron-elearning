<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $routeNamePart }} | Moncoran</title>
    <link rel="icon" href="{{ asset('images/image-21@2x.png') }}" />
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo Paaji 2:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" />
</head>
<body>
    <div>
        @yield('content')
    </div>

    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <!-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', '#updateRecord', function(){
                $('#UpdateModal').modal('show');
        });
        $(document).on('click', '.enrollCourse', function(){
            var courseId = $(this).attr("id");
            $.ajax({
            url: '/courses/' + courseId,
            method: 'GET',
            success: function(data) {
                $('#course-title').text(data.name);
                $('#course-description').text(data.description);
                $('#course-price').text(data.price);
                $('#course_id').val(courseId);
                $('#amount').val(data.price);
                $('#EnrollModal').modal('show');
            },
            error: function(xhr) {
                alert('Failed to fetch course details');
            }
        });
            //$('#EnrollModal').modal('show');
        });
    </script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <!-- Paystack Payment Script -->
    <script>
    function payWithPaystack() {        
        $('#EnrollModal').modal('hide');        
        var course_id = document.getElementById('course_id').value;
        var email = document.getElementById('email').value;
        var amount = document.getElementById('amount').value * 100; // Convert to kobo
        var handler = PaystackPop.setup({
            key: 'pk_test_88efe455a095b8460920d6bd51eefefd3a28e4dc', // Replace with your public key
            email: email,
            amount: amount,
            currency: "NGN",
            ref: 'MON'+Math.floor((Math.random() * 1000000000) + 1), // Generate a random reference number
            callback: function(response) {
                // This happens after the payment is completed successfully
                var reference = response.reference;
                // Make an AJAX call to your server with the reference to verify the payment
              //alert('Payment complete! Reference: ' + reference);
                console.log('Reference:', reference); // Debugging line
                console.log('Email:', email); // Debugging line
                console.log('Amount:', amount); // Debugging line
                console.log('Course ID:', course_id); // Debugging line

                $.ajax({
                    url: "{{ route('pay') }}",
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reference: reference,
                        email: email,
                        amount: amount / 100, // Convert back to Naira
                        course_id: course_id
                    },
                    success: function(response) {
                        console.log('Server Response:', response); // Debugging line

                        if (response.status) {
                            alert('Payment successful');
                            location.reload();
                        } else {
                            alert('Payment verification failed');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error); // Debugging line
                        console.error('Status:', status); // Debugging line
                        console.error('Response:', xhr.responseText); // Debugging line
                    }
                });
            },
            onClose: function() {
                alert('Transaction was not completed, window closed.');
            }
        });
        handler.openIframe();
    }
    </script>
    <script>
    $(document).ready(function() {
        // Escape quotes in the JSON string
        const courseJson = `{!! addslashes(json_encode($course)) !!}`;
        const course = JSON.parse(courseJson);
        const lessons = course.lessons;
        
        lessons.forEach((lesson, index) => {
            const video = $(`#video${lesson.id}`);
            const audio = $(`#audio${lesson.id}`);
            const button = $(`#button${lesson.id}`);
            
            if (video.length) {
                video.on('ended', function() {
                    updateProgress(lesson.id, index);
                });
            }
            
            if (audio.length) {
                audio.on('ended', function() {
                    updateProgress(lesson.id, index);
                });
            }
        });
        
        function updateProgress(lessonId, currentIndex) {
            $.ajax({
                url: "{{ route('updateprogress') }}",
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({ lesson_id: lessonId, course_id: course.id }),
                success: function(data) {
                    if (data.success) {
                        enableNextButton(currentIndex);
                    }
                }
            });
        }
        
        function enableNextButton(currentIndex) {
            if (currentIndex + 1 < lessons.length) {
                const nextLesson = lessons[currentIndex + 1];
                const nextButton = $(`#button${nextLesson.id}`);
                nextButton.prop('disabled', false);
            }
        }
    });
</script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>