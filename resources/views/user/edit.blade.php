@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <div class="tile-body">
                    @include('user.__form', ['context' => 'edit'])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        $('#upload').on('click', function(e) {
            e.preventDefault();

            var file = $('#photo-input')[0].files[0];
            var _token = '{{ csrf_token() }}';
            var formData = new FormData();
            var baseUploadFolder = '{{ asset('storage/uploads') }}';

            formData.append('photo', file);
            formData.append('_token', _token);

            $.ajax({
                url: '{{ url('user/' . (isset($user) ? $user->id : '') . '/photo/upload') }}',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type: 'post',
                success: function(hashedFileName) {
                    const alert = document.getElementById('uploadAlert')

                    alert.innerHTML = 'Upload realizado com sucesso!';
                    alert.classList.contains('alert-danger') && alert.classList.remove(
                        'alert-danger')
                    alert.classList += ' alert-success';
                    alert.classList.remove('d-none');

                    // alter user image
                    var img = document.getElementById('img-thumbnail');

                    img.src = baseUploadFolder + '/' + hashedFileName;

                    setTimeout(() => {
                        alert.classList += ' d-none';
                    }, 5000);
                },
                error: function() {
                    const alert = document.getElementById('uploadAlert')

                    alert.innerHTML = 'Erro ao realizar upload!';
                    alert.classList.contains('alert-success') && alert.classList.remove(
                        'alert-success')
                    alert.classList += 'alert-danger';
                    alert.classList.remove('d-none');

                    setTimeout(() => {
                        alert.classList += ' d-none';
                    }, 5000);
                }
            });
        });

        $("#remove-photo").on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ url('user/' . (isset($user) ? $user->id : '') . '/photo/remove') }}',
                cache: false,
                contentType: false,
                processData: false,
                data: {
                    id: {{ $user->id }}
                },
                type: 'get',
                success: function(message) {
                    const alert = document.getElementById('uploadAlert')

                    alert.innerHTML = message;
                    alert.classList.contains('alert-danger') && alert.classList.remove(
                        'alert-danger')
                    alert.classList += ' alert-success';
                    alert.classList.remove('d-none');

                    // alter user image
                    var img = document.getElementById('img-thumbnail');

                    img.src = '{{ asset('images/default-photo.jpg') }}';
                    setTimeout(() => {
                        alert.classList += ' d-none';
                    }, 5000);
                },
                error: function(message) {
                    const alert = document.getElementById('uploadAlert')

                    alert.innerHTML = message;
                    alert.classList.contains('alert-success') && alert.classList.remove(
                        'alert-success')
                    alert.classList += ' alert-danger';
                    alert.classList.remove('d-none');

                    setTimeout(() => {
                        alert.classList += ' d-none';
                    }, 5000);
                }
            })
        }); --}}
    </script>
@endpush
