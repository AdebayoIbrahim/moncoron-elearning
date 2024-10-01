<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/js/app.js','resources/css/custom-editor/editor.css','resources/css/app.css'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $routeNamePart ?? 'Moncoran' }} | Moncoran</title>
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
    <link href="https://fonts.cdnfonts.com/css/mona-sans" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @csrf
</head>

<body>
    <div id="app">
        @yield('content')
    </div>

    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- agora-video --}}
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.22.0.js"></script>

    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // Initialize Pusher
        // Pusher.logToConsole = true;

        // var pusher = new Pusher('{{ env('
        //     PUSHER_APP_KEY ') }}', {
        //         cluster: '{{ env('
        //         PUSHER_APP_CLUSTER ') }}'
        //         , encrypted: true
        //     });

        // var channel = pusher.subscribe('private-App.User.' + {
        //     Auth::id()
        // }
        // });
        // channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function(data) {
        //     alert(data.title + ': ' + data.message);
        //     // Update notification bell count
        //     var notificationCount = parseInt($('#notification-count').text());
        //     $('#notification-count').text(notificationCount + 1);

        //     // Add notification to the dropdown
        //     $('#notification-dropdown').prepend(
        //         '<a class="dropdown-item" href="' + data.url + '">' + data.message + '</a>'
        //     );
        // });

        $(document).on('click', '#updateRecord', function() {
            $('#UpdateModal').modal('show');
        });

        $(document).on('click', '.enrollCourse', function() {
            var courseId = $(this).attr("id");
            $.ajax({
                url: '/courses/' + courseId
                , method: 'GET'
                , success: function(data) {
                    $('#course-title').text(data.name);
                    $('#course-description').text(data.description);
                    $('#course-price').text(data.price);
                    $('#course_id').val(courseId);
                    $('#amount').val(data.price);
                    $('#EnrollModal').modal('show');
                }
                , error: function(xhr) {
                    alert('Failed to fetch course details');
                }
            });
        });

        function payWithPaystack() {
            $('#EnrollModal').modal('hide');
            var course_id = document.getElementById('course_id').value;
            var email = document.getElementById('email').value;
            var amount = document.getElementById('amount').value * 100; // Convert to kobo
            var handler = PaystackPop.setup({
                key: 'pk_test_88efe455a095b8460920d6bd51eefefd3a28e4dc', // Replace with your public key
                email: email
                , amount: amount
                , currency: "NGN"
                , ref: 'MON' + Math.floor((Math.random() * 1000000000) + 1), // Generate a random reference number
                callback: function(response) {
                    var reference = response.reference;
                    $.ajax({
                        url: "{{ route('pay') }}"
                        , method: 'post'
                        , data: {
                            _token: '{{ csrf_token() }}'
                            , reference: reference
                            , email: email
                            , amount: amount / 100, // Convert back to Naira
                            course_id: course_id
                        }
                        , success: function(response) {
                            if (response.status) {
                                alert('Payment successful');
                                location.reload();
                            } else {
                                alert('Payment verification failed');
                            }
                        }
                        , error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            console.error('Status:', status);
                            console.error('Response:', xhr.responseText);
                        }
                    });
                }
                , onClose: function() {
                    alert('Transaction was not completed, window closed.');
                }
            });
            handler.openIframe();
        }

        @if(isset($course))
        $(document).ready(function() {
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
                    url: "{{ route('updateprogress') }}"
                    , type: 'POST'
                    , contentType: 'application/json'
                    , dataType: 'json'
                    , headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                    , data: JSON.stringify({
                        lesson_id: lessonId
                        , course_id: course.id
                    })
                    , success: function(data) {
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
        @endif

    </script>

    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>

</html>
