@extends('app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="tile">
                <x-alert />
                <div class="tile-body d-flex flex-row">
                    <div class="d-flex align-items-center col-md-10" style="padding: 0;">
                        <form id="search" class="input-group mb-3" action="{{ url('user/search') }}" method="GET">
                            @csrf
                            <input name="offset" type="number" class="form-control " placeholder="Offset"
                                aria-label="Offset" style="max-width: 8rem;">
                            <input name="limit" type="number" class="form-control" placeholder="Limit" aria-label="Limit"
                                style="max-width: 8rem;">
                            <input name="search" type="text" class="form-control" placeholder="Search for..."
                                aria-label="Search for...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Button</button>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex flex-row-reverse" style="width: 100%">
                        <a href="{{ url('user/new') }}" class="btn btn-primary mb-3 btn-md">
                            <i class="fa fa-plus-circle"></i>
                            <span class="ml-2 flex-nowrap">Inserir usuário</span>
                        </a>
                    </div>
                </div>
                @if (count($users) === 0)
                    <div class="container px-0" id="table-message">
                        <div class="alert alert-warning" role="alert">
                            Nenhum usuário encontrado.
                        </div>
                    </div>
                @else
                    <table id="table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="sort-btn active" data-row="0">
                                    Id
                                    <i class="fa fa-arrow-down"></i>
                                </th>
                                <th class="sort-btn" data-row="1">
                                    Nome
                                    <i class="fa fa-arrow-down"></i>
                                </th>
                                <th class="sort-btn" data-row="2">
                                    Email
                                    <i class="fa fa-arrow-down"></i>
                                </th>
                                <th class="fake-sort-btn">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="{{ url('user/edit', $user->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-pencil m-0" style="min-width: 2rem"></i>
                                            </a>
                                            <a href="{{ url('user/delete', $user->id) }}" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash m-0" style="min-width: 2rem"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.sort-btn').click(function() {
            // get the row number
            var row = $(this).data('row');

            // remove the active class from all the buttons
            $('.sort-btn').removeClass('active');

            // add the active class to the clicked button
            $(this).addClass('active');

            // get lines from table
            var lines = $('#table tbody tr').get();

            // sort the lines based on columns number
            lines.sort((a, b) => {
                // get the text of the columns
                a = $(a).children('td').eq(row).text();
                b = $(b).children('td').eq(row).text();

                // if the text is a number, convert to number
                if (!isNaN(a) && !isNaN(b)) {
                    a = parseInt(a);
                    b = parseInt(b);
                }

                // if the text is a date, convert to date
                if (Date.parse(a) && Date.parse(b)) {
                    a = new Date(a);
                    b = new Date(b);
                }

                // if the text is a string, convert to string
                if (typeof a === 'string' && typeof b === 'string') {
                    a = a.toUpperCase();
                    b = b.toUpperCase();
                }

                // compare the values
                if (a < b) {
                    return -1;
                }
                if (a > b) {
                    return 1;
                }

                return 0;
            })

            // while has childres remove them, ignoring 1st th
            while ($('#table tbody').children().length > 0) {
                $('#table tbody').children().remove();
            }

            // append the lines to the table
            $.each(lines, function(index, line) {
                $('#table tbody').append(line);
            });
        });
    </script>
@endpush
