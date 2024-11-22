@extends('layout.mainlayout')
@section('title', 'Online Coaching')

@section('content')

    <div class="container">
        <h1>Online Coaching Page</h1>
        <div class="m-auto">
            <!-- Bootstrap Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Body Type</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Weight</th>
                        <th>Height</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coaches as $p)
                        <tr>
                            <td>{{ $p->id }}</td> <!-- Display ID -->
                            <td>{{ $p->bodyType }}</td> <!-- Display Body Type -->
                            <td>{{ $p->gender }}</td> <!-- Display Gender -->
                            <td>{{ $p->age }}</td> <!-- Display Age -->
                            <td>{{ $p->weight }}</td> <!-- Display Weight -->
                            <td>{{ $p->height }}</td> <!-- Display Height -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$coaches->links('pagination::bootstrap-5')}}
        </div>
    </div>

@endsection
