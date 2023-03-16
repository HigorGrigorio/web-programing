<div class="row">
    <div class="col-md-4">
        <div class="d-flex flex-column gap-3">
            <img id="img-thumbnail" class="img-thumbnail mt-3"
                src="{{ asset($user->photo ? 'storage/uploads/' . $user->photo : 'images/default-photo.jpg') }}"
                alt="Profile Image">
            <input type="file" class="mt-3" name="photo "id="photo-input" accept="image/*" required>
            <div class="btn-group btn-group-toggle">
                <button type="submit" id="upload" class="btn btn-success align-middle mt-3" title="Upload de Fotos">
                    <i class="fa fa-upload"></i>
                </button>
                <button type="button" id="remove-photo" class="btn btn-danger align-middle mt-3"
                    title="Upload de Fotos">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <h3 class="mb-4">Editando usuário {{ $user->name }}.</h3>
        <form action="{{ url('user/update', $user->id) }}" method="post" id="form">
            @csrf
            <div class="form-group" @error('name') invalid @enderror>
                <label for="name" class="control-label">Nome</label>
                <input type="text" class="form-control
                    @error('name') is-invalid @enderror"
                    name="name" placeholder="Nome" value="{{ $user->name }}">
                @error('name')
                    @foreach ($errors->get('name') as $error)
                        <span class="invalid-feedback" role="alert">
                            {{ $error }}
                        </span>
                    @endforeach
                @enderror
            </div>
            <div class="form-group" @error('email') invalid @enderror>
                <label for="email" class="control-label">Email</label>
                <input type="text" class="form-control
                    @error('email') is-invalid @enderror"
                    name="email" placeholder="Email" value="{{ $user->email }}">
                @error('email')
                    @foreach ($errors->get('email') as $error)
                        <span class="invalid-feedback" role="alert">
                            {{ $error }}
                        </span>
                    @endforeach
                @enderror
            </div>
            <div class="d-flex justify-content-center justify-content-between pt-3">
                <button type="submit" class="btn btn-primary btn-lg">Salvar</button>
                <a href="{{ url('/users') }}" class="btn btn-danger btn-lg">Cancelar</a>
            </div>
            <div class="col-md-12" id="notify-container">
                <div id="uploadAlert" class="alert alert-danger mt-3 d-none" role="alert">
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
    <script>
        /**
         * When the user clicks on the remove photo button
         * the current photo is removed and the default photo is displayed
         */
        let current = null;
        const defaultPhoto = "{{ asset('images/default-photo.jpg') }}";

        const makeAlert = (context, value) => {
            return `
                <div class="alert alert-${context} mt-2" role="alert">
                    ${value}
                </div>
            `;
        }

        const notifySuccess = (message) => {
            const notify = makeAlert('success', message);

            $('#notify-container').append(notify);

            setTimeout(() => {
                $('#notify-container>div:first-child').remove()
            }, 5000);
        }

        const notifyError = (message) => {
            const alert = document.getElementById('uploadAlert')

            const notify = makeAlert('danger', message);

            $('#notify-container').append(notify);

            setTimeout(() => {
                $('#notify-container>div:first-child').remove()
            }, 5000);
        }

        // save a copy of the current photo from img tuumbnail
        $(window).on('load', () => {
            photoUrl = $('#img-thumbnail').attr('src');

            // download the photo
            $.ajax({
                url: photoUrl,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(blob, status, xhr) {
                    current = new Blob([blob], {
                        type: xhr.getResponseHeader('Content-Type')
                    });

                    console.log(current);
                },
                error: function(xhr, status, error) {
                    notifyError('Não foi possível carregar a foto atual.');
                }
            });

        })

        const priviewImage = (url) => {
            $('#img-thumbnail').attr('src', url);
        }

        const updateUserPhoto = (photo) => {

            if (!photo) {
                return;
            }

            formData = new FormData();
            formData.append('photo', photo, 'photo.jpg');
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ url('user/' . (isset($user) ? $user->id : '-1') . '/photo/upload') }}',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type: 'post',
                success: function(hashedFileName) {
                    // alter user image url
                    const url = '{{ asset('storage/uploads') }}' + '/' + hashedFileName;

                    priviewImage(url);

                    notifySuccess('Foto atualizada com sucesso!');
                    console.log('success');
                },
                error: function() {
                    notifyError('Erro ao atualizar foto!');
                    console.log('error');
                }
            });
        }

        $("#upload").click((e) => {
            e.preventDefault();

            const photo = document.getElementById('photo-input').files[0];
            updateUserPhoto(photo);
        });

        $('#remove-photo').click((e) => {
            e.preventDefault();

            const photo = current ?? defaultPhoto;

            updateUserPhoto(current);
        });
    </script>
@endpush
