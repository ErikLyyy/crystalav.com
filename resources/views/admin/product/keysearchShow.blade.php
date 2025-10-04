@extends('layouts.adminLayout')
@section('title', 'Keysearch for Product')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">List keysearch</h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-success">{{ session('success') }}
                    </div>
                @elseif(session('danger'))
                    <div style="padding:10px 15px; margin-bottom:0px" class="alert alert-danger">{{ session('danger') }}
                    </div>
                @endif
                <form action="{{ url('admin/product/keysearch/action') }}" method="GET">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="actions">
                            <option>Actions</option>
                            <option value="delete">Delete</option>
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
                                <th scope="col">Keyword</th>
                                <th scope="col">Result</th>
                                <th scope="col">Search count</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $t = 0;
                            @endphp
                            @foreach ($list_keysearches as $keysearch)
                                @php
                                    $t++;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkItem[]" value="{{ $keysearch->id }}">
                                    </td>
                                    <td scope="row">{{ $t }}</td>

                                    <td style="display: flex; justify-content: space-between;">
                                        <span>{{ $keysearch->keyword }}</span>
                                        <div class="action-wp">
                                            <a href="{{ url('admin/product/keysearch/delete/' . $keysearch->id) }}"
                                                class="action" title="Delete" style="margin-right: 20px">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                    viewBox="0 -960 960 960" width="20px" fill="#000">
                                                    <path
                                                        d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.57Q720-186 698.85-165T648-144H312Zm336-552H312v480h336v-480ZM384-288h72v-336h-72v336Zm120 0h72v-336h-72v336ZM312-696v480-480Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    <td>{{ $keysearch->result }}</td>
                                    <td>{{ $keysearch->search_count }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection
