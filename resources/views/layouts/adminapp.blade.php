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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo Paaji 2:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" />


    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <!-- Add this in your header -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emoji-picker/2.3.3/css/emoji.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emoji-picker/2.3.3/js/config.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emoji-picker/2.3.3/js/util.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emoji-picker/2.3.3/js/jquery.emojiarea.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emoji-picker/2.3.3/js/emoji-picker.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



</head>

<body>
    <div>
        @yield('content')
    </div>

    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <!-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript">
    $(document).on('click', '#addStudent', function() {
        $('#addStudentModal').modal('show');
    });
    $(document).on('click', '#addLecturer', function() {
        $('#addLecturerModal').modal('show');
    });
    $(document).on('click', '#addTeacher', function() {
        $('#addTeacherModal').modal('show');
    });
    $(document).on('click', '#addCourse', function() {
        $('#addCourseModal').modal('show');
    });
    $(document).on('click', '.editCourse', function() {
        var courseId = $(this).attr("id");
        $.ajax({
            url: '/courses/' + courseId,
            method: 'GET',
            success: function(data) {
                $('#name').val(data.name);
                $('#description').text(data.description);
                $('#price').val(data.price);
                $('#slug').val(data.slug);
                $('#age_group').val(data.age_group);
                if (data.age_group == 0) {
                    $('#age_group').html('All Age Group');
                } else if (data.age_group == 1) {
                    $('#age_group').html('Below 18');
                } else {
                    $('#age_group').html('18 Above');
                }

                if (data.all_lessons_paid == 0) {
                    $('#all_lessons_paid').val(0);
                    $('#all_lessons_paid').html('No');
                } else {
                    $('#all_lessons_paid').val(1);
                    $('#all_lessons_paid').html('Yes');
                }

                if (data.is_locked == 0) {
                    $('#is_locked').val(0);
                    $('#is_locked').html('No');
                } else {
                    $('#is_locked').val(1);
                    $('#is_locked').html('Yes');
                }

                $('#capacity').val(data.capacity);
                $('#duration').val(data.duration);
                $('#course_id').val(courseId);
                $('#editCourseModal').modal('show');
            },
            error: function(xhr) {
                alert('Failed to fetch course details');
            }
        });
        //$('#editCourseModal').modal('show');
    });
    $(document).on('click', '#addLesson', function() {
        $('#addLessonModal').modal('show');
    });
    </script>
    <script src="{{ asset('js/sidebar.js') }}"></script>


</body>


</html>