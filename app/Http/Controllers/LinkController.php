<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $url = "https://timesofindia.indiatimes.com/rssfeeds/-2128838597.cms?feedtype=json";
        $response = Http::get($url);
        $data = $response->json();


        $items = $data['channel']['item'] ?? [];

        $collection = collect($items);

        if ($request->has('search')) {
            $search = $request->input('search');
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['dc:creator']['#text'] ?? 'Unknown'), strtolower($search));
            });
        }
        if ($request->has('sort') && in_array($request->input('sort'), ['asc', 'desc'])) {
            $sort = $request->input('sort');
            $sortField = $request->input('sort_field', 'dc:creator.#text');

            if ($sortField === 'pubDate') {
                $collection = $collection->sortBy(function ($item) {
                    return strtotime($item['pubDate']);
                });
                if ($sort === 'desc') {
                    $collection = $collection->reverse();
                }
            } else {
                $collection = $sort === 'asc'
                    ? $collection->sortBy($sortField)
                    : $collection->sortByDesc($sortField);
            }
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paginatedItems = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('main', ['data' => $paginatedItems]);
    }
}
