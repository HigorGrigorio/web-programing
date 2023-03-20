<div class="row">
    <div class="col-md-4">
        <div class="d-flex flex-column gap-3 align-items-center">
            <img id="img-thumbnail" class="img-thumbnail mt-3"
                style="width: 15rem; height: auto; max-height: 15rem; object-fit: cover;"
                src="{{ asset(isset($user) && $user->photo ? 'storage/uploads/' . $user->photo : 'images/default-photo.jpg') }}"
                alt="Profile Image">
            <input type="file" class="mt-3" name="photo "id="photo-input" accept="image/*" required
                style="width: 15rem">
            <div class="btn-group btn-group-toggle container">
                @if ($context == 'edit')
                    <button type="submit" id="upload" class="btn btn-success align-middle mt-3"
                        title="Upload de Fotos">
                        <i class="fa fa-upload"></i>
                    </button>
                @endif
                <button type="button" id="remove-photo" class="btn btn-danger align-middle mt-3 "
                    title="Upload de Fotos">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-8 py-3">
        @if ($context == 'edit')
            <h3 class="mb-4">Editando usuário {{ $user->name }}.</h3>
        @endif
        <x-form :action="$action" :id="'form'" :fields="[
            [
                'name' => 'name',
                'label' => 'Nome',
                'placeholder' => 'Nome',
                'value' => isset($user) ? $user->name : '',
            ],
            [
                'name' => 'email',
                'label' => 'Email',
                'placeholder' => 'Email',
                'value' => isset($user) ? $user->email : '',
            ],
        ]" :submit="[
            'label' => 'Salvar',
        ]" :previous="[
            'url' => url('/users'),
            'label' => 'Cancelar',
        ]" />
    </div>
</div>

@push('scripts')
    <script>
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

        const priviewImage = (url) => {
            $('#img-thumbnail').attr('src', url);
        }


        // When any files are selected in the input field with id photo-input
        $('#photo-input').change((e) => {
            e.preventDefault();

            const file = e.target.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = (e) => {
                priviewImage(e.target.result);
            }

            reader.readAsDataURL(file);
        });
    </script>
@endpush

@if ($context == 'edit')
    @push('scripts')
        <script>
            /**
             * When the user clicks on the remove photo button
             * the current photo is removed and the default photo is displayed
             */
            let current = null;

            // save a copy of the current photo from img thumbnail
            const saveCurrentPhoto = () => {
                photoUrl = $('#img-thumbnail').attr('src');

                // for default photo
                if (photoUrl.includes('default-photo.jpg')) {
                    return false;
                }

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

                    },
                    error: function(xhr, status, error) {
                        notifyError('Não foi possível carregar a foto atual.');
                    }
                });

                return true;
            }

            const updateUserPhoto = (photo) => {
                if (!photo) {
                    $.ajax({
                        url: '{{ url('user/' . (isset($user) ? $user->id : '-1') . '/photo/remove') }}',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        type: 'get',
                        success: function(message) {
                            // alter user image url
                            const url = '{{ asset('images/default-photo.jpg') }}';

                            priviewImage(url);
                            notifySuccess(message);
                        },
                        error: notifyError
                    });

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

                if (current === null) {
                    saveCurrentPhoto();
                }

                const photo = document.getElementById('photo-input').files[0];

                if (!photo) {
                    notifyError('Nenhuma foto selecionada.')
                    return;
                }

                updateUserPhoto(photo);
            });

            $('#remove-photo').click((e) => {
                e.preventDefault();

                if (current === null && !saveCurrentPhoto()) {
                    notifyError('Nenhuma foto selecionada.');
                    return;
                }

                const photo = current;
                current = null;

                updateUserPhoto(current);
            });

            $(window).on('load', () => {
                saveCurrentPhoto();
            });
        </script>
    @endpush
@endif

@if ($context == 'store')
    @push('scripts')
        <script>
            const resetForm = () => {
                $('#form').trigger('reset');
                $('#img-thumbnail').attr('src', '{{ asset('images/default-photo.jpg') }}');
            }

            $('#remove-photo').click((e) => {
                e.preventDefault();

                const input = $('#photo-input');

                // check if there is a photo selected
                const file = input.val()

                if (!file) {
                    notifyError('Nenhuma foto selecionada.')
                    return;
                }

                // remove the photo from input field
                input.val('');

                const url = '{{ asset('images/default-photo.jpg') }}';

                priviewImage(url);
            });

            // When submit form, check if there is a photo selected and make a new form data with the photo
            $('#form').submit((e) => {
                e.preventDefault();

                const form = e.target;
                const input = $('#photo-input')[0].cloneNode(true);

                input.hidden = true;

                form.append(input);

                form.submit();

            });
        </script>
    @endpush
@endif
