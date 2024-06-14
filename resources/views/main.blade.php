<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mumbai News </title>
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4 text-center font-weight-bold text-primary">Mumbai News </h1>

       
        <form method="GET" action="{{ url('/') }}" class="mb-4">
            <div class="form-row">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request()->input('search') }}" class="form-control" placeholder="Search by Creator">
                </div>
                <div class="col-md-3">
                    <select name="sort_field" class="form-control">
                        <option value="" disabled selected>Sort by</option>
                        <option value="dc:creator.#text" {{ request()->input('sort_field') == 'dc:creator.#text' ? 'selected' : '' }}>Creator</option>
                        <option value="pubDate" {{ request()->input('sort_field') == 'pubDate' ? 'selected' : '' }}>Publication Date</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-control">
                        <option value="" disabled selected>Order</option>
                        <option value="asc" {{ request()->input('sort') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request()->input('sort') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Apply</button>
                </div>
            </div>
        </form>

       
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Image</th>
                        <th scope="col">Description</th>
                        <th scope="col">Creator</th>
                        <th scope="col">Publication Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if($data->isNotEmpty())
                        @foreach($data as $item)
                            <tr>
                                <td><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></td>
                                <td>
                                    @if(isset($item['enclosure']['@url']))
                                        <img src="{{ $item['enclosure']['@url'] }}" alt="Image Not Available" class="img-thumbnail" style="max-width: 100px;">
                                    @else
                                        <p>Image not found</p>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($item['description']))
                                        {!! preg_replace('/<img[^>]+\>/i', '', $item['description']) !!}
                                    @else
                                        <p>Description not available</p>
                                    @endif
                                </td>
                                <td>{{ $item['dc:creator']['#text'] ?? 'Unknown' }}</td>
                                <td>{{ $item['pubDate'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No results found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        
        <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                @if ($data->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $data->previousPageUrl() }}" rel="prev">&laquo; Previous</a></li>
                @endif

                @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                    @if ($page == $data->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                @if ($data->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $data->nextPageUrl() }}" rel="next">Next &raquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>

  
   
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
