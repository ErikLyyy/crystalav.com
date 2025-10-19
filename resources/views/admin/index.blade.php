@extends('layouts.adminLayout')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Total Product</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalProduct }}</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">Total Request</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalRequest }}</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">Total contact</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalContact }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- end analytic  -->
        <div class="card">
            <div class="card-header font-weight-bold">
                List Request
            </div>
            <div class="card-body">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Company Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Approximate Date Needed</th>
                            <th scope="col">Approximate Return Date</th>
                            <th scope="col">Sent at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $t = ($currentPage - 1) * 20;
                        @endphp
                        @foreach ($list_request as $request)
                            @php
                                $t++;
                            @endphp
                            <tr>
                                <td>{{ $t }}</td>
                                <td>{{ $request->first_name }}</td>
                                <td>{{ $request->last_name }}</td>
                                <td>{{ $request->company_name }}</td>
                                <td>{{ $request->email }}</td>
                                <td>{{ $request->phone_number }}</td>
                                <td>{{ $request->approximate_date }}</td>
                                <td>{{ $request->approximate_return }}</td>
                                <td style="display: flex; justify-content: space-between;">
                                    <span>{{ $request->created_at }}</span>

                                    <div class="action-wp">
                                        <a href="{{ url('admin/request/read/' . $request->id) }}" class="action"
                                            title="Read">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20"
                                                viewBox="0 0 640 640" fill="#000">
                                                <path
                                                    d="M192 112L304 112L304 200C304 239.8 336.2 272 376 272L464 272L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 128C176 119.2 183.2 112 192 112zM352 131.9L444.1 224L376 224C362.7 224 352 213.3 352 200L352 131.9zM192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 250.5C512 233.5 505.3 217.2 493.3 205.2L370.7 82.7C358.7 70.7 342.5 64 325.5 64L192 64zM248 320C234.7 320 224 330.7 224 344C224 357.3 234.7 368 248 368L392 368C405.3 368 416 357.3 416 344C416 330.7 405.3 320 392 320L248 320zM248 416C234.7 416 224 426.7 224 440C224 453.3 234.7 464 248 464L392 464C405.3 464 416 453.3 416 440C416 426.7 405.3 416 392 416L248 416z" />
                                            </svg>
                                        </a>
                                        <a href="{{ url('admin/request/delete/' . $request->id) }}" class="action"
                                            title="Delete" style="margin-right: 20px">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                                width="20px" fill="#000">
                                                <path
                                                    d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
                {{ $list_request->onEachSide(2)->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}

            </div>
        </div>

    </div>

@endsection
