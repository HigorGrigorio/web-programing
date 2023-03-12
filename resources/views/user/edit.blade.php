@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-3">
                                <img id="img-fluid" class="img-thumbnail mt-3"
                                    src="{{ asset($user->photo ? 'uploads/' . $user->photo : 'images/default-photo.jpg') }}"
                                    alt="Profile Image">
                                <input type="file" class="mt-3" name="photo "id="photo-input" accept="image/*"
                                    required>
                                <button type="submit" id="upload" class="btn btn-success align-middle mt-3"
                                    title="Upload de Fotos">
                                    <i class="fa fa-upload"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-4">Editando usuário {{ $user->name }}.</h3>
                            <form action="{{ url('user/update', $user->id) }}" method="post">
                                @csrf
                                <div class="form-group" @error('name') invalid @enderror>
                                    <label for="name" class="control-label">Nome</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" placeholder="Nome" value="{{ $user->name }}">
                                    @error('name')
                                        <div class="alert alert-danger mt-2" role="alert">
                                            @foreach ($errors->get('name') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group" @error('email') invalid @enderror>
                                    <label for="email" class="control-label">Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        name="email" placeholder="Email" value="{{ $user->email }}">
                                    @error('email')
                                        <div class="alert alert-danger mt-2" role="alert">
                                            @foreach ($errors->get('email') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-center justify-content-between pt-3">
                                    <button type="submit" class="btn btn-primary btn-lg">Editar</button>
                                    <a href="{{ url('/users') }}" class="btn btn-danger btn-lg">Cancelar</a>
                                </div>
                                <div id="uploadAlert" class="alert alert-danger mt-3 d-none" role="alert">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#upload').on('click', function(e) {
            e.preventDefault();

            var file = $('#photo-input')[0].files[0];
            var _token = '{{ csrf_token() }}';
            var formData = new FormData();
            var baseUploadFolder = '{{ asset('/uploads') }}';

            formData.append('photo', file);
            formData.append('_token', _token);

            $.ajax({
                url: '{{ url('user/upload/photo' . (isset($user) ? '/' . $user->id : '')) }}',
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
                    }, 3000);
                },
                error: function() {
                    const alert = document.getElementById('uploadAlert')

                    alert.innerHTML = 'Erro ao realizar upload!';
                    alert.classList.contains('alert-success') && alert.classList.remove(
                        'alert-succes')
                    alert.classList += 'alert-danger';
                    alert.classList.remove('d-none');

                    setTimeout(() => {
                        alert.classList += ' d-none';
                    }, 3000);
                }
            });
        });
    </script>
@endpush
