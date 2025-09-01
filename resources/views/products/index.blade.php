<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Product</h1>
    <div>
        @if(session()->has('success'))
        <div>
            {{ session('success') }}
        </div>
        @endif
    </div>
    <div>
        <div>
<a href="{{ route('product.create') }}">Login Form</a>
    </div>
        <table border="2">
            <tr>
       <th>Id</th>
       <th>Username</th>
       <th>Password</th>
       <th>Edit</th>
       <th>Delete</th>
        </tr>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->username }}</td>
            <td>{{ $product->password}}</td>
            <td>
                <a href="{{ route('product.edit',$product) }}">Edit</a>
            </td>
            <td>
                <form method="post" action="{{ route('product.destroy',['product'=>$product])}}">
                    @csrf
                    @method('delete')
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    </div>

</body>
</html>