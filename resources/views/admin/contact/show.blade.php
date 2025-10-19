@extends('layouts.adminLayout')
@section('title', 'Contact')
@section('content')
    <style>
        .name {
            width: 20%;
        }

        .message {
            width: 50%;
        }

        .message p {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">List Contacts</h5>
                <div class="form-search form-inline">
                    <form method="GET">
                        @if ($trash == true)
                            <input type="hidden" name="status" value="trash" />
                        @endif
                        <input type="search" name="search" class="form-control form-search" placeholder="Search">
                        <input type="submit" name="btn_search" value="Search" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}
                    </div>
                @elseif(session('danger'))
                    <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-danger">{{ session('danger') }}
                    </div>
                @endif
                <form action="{{ url('admin/contact/action') }}" method="GET">

                    <div class="analytic">
                        <a href="{{ url('admin/contact') }}" class="text-primary">All<span
                                class="text-muted">({{ $countItem }})</span></a>
                        <a href="{{ url('admin/contact?status=trash') }}" class="text-primary">
                            Trash<span class="text-muted">({{ count($list_trash) }})</span>
                        </a>
                    </div>
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="actions">
                            <option>Actions</option>
                            @foreach ($actions as $k => $action)
                                <option value={{ $k }}>{{ $action }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn_apply" value="Apply" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col" class="name">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col" class="message">Message</th>
                                <th scope="col">Sent at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $t = ($currentPage - 1) * 20;
                            @endphp
                            @foreach ($list_contact as $contact)
                                @php
                                    $t++;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkItem[]" value="{{ $contact->id }}">
                                    </td>
                                    <td>{{ $t }}</td>
                                    <td class="name">{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td class="message">
                                        <p>{{ $contact->message }}</p>
                                    </td>
                                    <td style="display: flex; justify-content: space-between;">
                                        <span>{{ $contact->created_at }}</span>

                                        <div class="action-wp">
                                            @if ($trash == true)
                                                <a href="{{ url('admin/contact/restore/' . $contact->id) }}" class="action"
                                                    title="Restore">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M479.79-408Q450-408 429-429.21t-21-51Q408-510 429.21-531t51-21Q510-552 531-530.79t21 51Q552-450 530.79-429t-51 21Zm.21 264q-140 0-238.5-98T144-480h72q2 110 78.5 187T480-216q110.31 0 187.16-76.78 76.84-76.78 76.84-187T667.16-667Q590.31-744 480-744q-59 0-111.5 25.5T277-648h107v72H144v-240h72v130q47.91-62.09 116.95-96.04Q402-816 480-816q70 0 131.13 26.6 61.14 26.6 106.4 71.87 45.27 45.26 71.87 106.4Q816-550 816-480t-26.6 131.13q-26.6 61.14-71.87 106.4-45.26 45.27-106.4 71.87Q550-144 480-144Z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            <a href="{{ url('admin/contact/read/' . $contact->id) }}" class="action"
                                                title="Read">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20"
                                                    viewBox="0 0 640 640" fill="#000">
                                                    <path
                                                        d="M192 112L304 112L304 200C304 239.8 336.2 272 376 272L464 272L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 128C176 119.2 183.2 112 192 112zM352 131.9L444.1 224L376 224C362.7 224 352 213.3 352 200L352 131.9zM192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 250.5C512 233.5 505.3 217.2 493.3 205.2L370.7 82.7C358.7 70.7 342.5 64 325.5 64L192 64zM248 320C234.7 320 224 330.7 224 344C224 357.3 234.7 368 248 368L392 368C405.3 368 416 357.3 416 344C416 330.7 405.3 320 392 320L248 320zM248 416C234.7 416 224 426.7 224 440C224 453.3 234.7 464 248 464L392 464C405.3 464 416 453.3 416 440C416 426.7 405.3 416 392 416L248 416z" />
                                                </svg>
                                            </a>
                                            @if ($trash == true)
                                                <a href="{{ url('admin/contact/forceDelete/' . $contact->id) }}"
                                                    class="action" title="Delete" style="margin-right: 20px">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="{{ url('admin/contact/delete/' . $contact->id) }}" class="action"
                                                    title="Delete" style="margin-right: 20px">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                        viewBox="0 -960 960 960" width="20px" fill="#000">
                                                        <path
                                                            d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </form>

                {{ $list_contact->onEachSide(2)->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}

            </div>
        </div>
    </div>
@endsection
