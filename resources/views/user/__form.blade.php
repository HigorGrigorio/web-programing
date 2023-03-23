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
            console.log(message)
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
            let photos = [];

            /**
             * When the user clicks on the cancel button, if has a photo saved
             * on cache it is restored.
             *
             * @type Array<Blob> An arrays of blobs.
             */
            let cache = null;

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
                    success: (blob, status, xhr) => {
                        photos.push(new Blob([blob], {
                            type: xhr.getResponseHeader('Content-Type')
                        }));

                        if (!cache) {
                            cache = photos[0];
                        }
                    },
                    error: function(xhr, status, error) {
                        notifyError('Não foi possível carregar a foto atual.');
                    }
                });

                return true;
            }

            // remove current photo
            const removeUserPhoto = () => {
                // update user image for default if not has a old photo saved
                let url;

                if (photos.length == 0) {
                    url = '{{ asset('images/default-photo.jpg') }}';
                } else {
                    url = URL.createObjectURL(photos.pop());
                }

                priviewImage(url);

                // delete from server
                $.ajax({
                    url: '{{ url('user/' . (isset($user) ? $user->id : '-1') . '/photo/remove') }}',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    type: 'get',
                    success: function(result) {
                        notifySuccess(result.success);
                    },
                    error: result => notifyError(result)
                });
            }

            const updateUserPhoto = (photo, notify = true) => {
                if (!photo) {
                    notifyError('Nenhuma foto selecionada.');
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
                    success: function(result) {
                        // alter user image url
                        const url = '{{ asset('storage/uploads') }}' + '/' + result.filename;

                        priviewImage(url);

                        if (notify)
                            notifySuccess(result.success);
                    },
                    error: function(result) {
                        console.log(e);
                        if (result.fail && notify)
                            notifyError(result.fail);
                    }
                });
            }


            $("#upload").click((e) => {
                e.preventDefault();

                saveCurrentPhoto();

                const photo = document.getElementById('photo-input').files[0];

                if (!photo) {
                    notifyError('Nenhuma foto selecionada.')
                    return;
                }

                updateUserPhoto(photo);
            });

            $('#remove-photo').click((e) => {
                e.preventDefault();

                removeUserPhoto();
            });

            $("#previous").click(e => {
                e.preventDefault();

                if (cache) {
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
                        success: function(result) {
                            // alter user image url
                            const url = '{{ asset('storage/uploads') }}' + '/' + result.filename;

                            priviewImage(url);

                            if (notify)
                                notifySuccess(result.success);
                        },
                        error: function(result) {
                            console.log(e);
                            if (result.fail && notify)
                                notifyError(result.fail);
                        }
                    });
                } else {
                    //redirect to btn href
                    window.location.href = $('#previous').attr('href');
                }
            })
        </script>
    @endpush
@endif

@if ($context == 'store')
    @push('scripts')
        <script>
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
