<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Visitor\VisitorStoreRequest;
use App\Http\Requests\Admin\Visitor\VisitorUpdateRequest;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Services\OpenAIService;

class VisitorController extends Controller
{
    protected $openAIService;

    public function __construct( OpenAIService $openAIService )
    {
        $this->openAIService = $openAIService;
    }
    
    /**
     * Display a listing of visitors.
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('visitor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sortableColumns = ['id', 'ip_address', 'conversation_id', 'created_at'];
        $visitors = Visitor::query();

        if ($request->filled('sort') && in_array($request->get('sort'), $sortableColumns)) {
            $visitors->orderBy($request->get('sort'), $request->get('direction', 'asc'));
        } else {
            $visitors->latest();
        }

        $visitors = $visitors->paginate(10);

        return view('admin.visitors.index', compact('visitors'));
    }

    /**
     * Show the form for creating a new visitor.
     */
    public function create()
    {
        abort_if(Gate::denies('visitor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.visitors.create');
    }

    /**
     * Store a newly created visitor.
     */
    public function store(VisitorStoreRequest $request)
    {
        abort_if(Gate::denies('visitor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validated();

        Visitor::create($data);

        return redirect()
            ->route('admin.visitors.index')
            ->with('success', 'Visitor created successfully.');
    }

    /**
     * Display the specified visitor.
     */
    public function show(Visitor $visitor)
    {
        abort_if(Gate::denies('visitor_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.visitors.show', compact('visitor'));
    }

    /**
     * Show the form for editing the specified visitor.
     */
    public function edit(Visitor $visitor)
    {
        abort_if(Gate::denies('visitor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.visitors.edit', compact('visitor'));
    }

    /**
     * Update the specified visitor.
     */
    public function update(VisitorUpdateRequest $request, Visitor $visitor)
    {
        abort_if(Gate::denies('visitor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validated();

        $visitor->update($data);

        return redirect()
            ->route('admin.visitors.index')
            ->with('success', 'Visitor updated successfully.');
    }

    /**
     * Remove the specified visitor from storage.
     */
    public function destroy(Visitor $visitor)
    {
        abort_if(Gate::denies('visitor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $visitor->delete();

        return redirect()
            ->route('admin.visitors.index')
            ->with('success', 'Visitor deleted successfully.');
    }
}
