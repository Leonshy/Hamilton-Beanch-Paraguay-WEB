<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Traits\HandlesOrder;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use HandlesOrder;
    public function index()
    {
        $faqs = Faq::orderBy('order')->paginate(30);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $nextOrder = $this->nextOrder(Faq::class);
        return view('admin.faqs.form', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $data = $this->validateFaq($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? $this->nextOrder(Faq::class);
        $this->shiftOrderUp(Faq::class, (int) $data['order']);
        Faq::create($data);
        return redirect()->route('admin.faqs.index')->with('success', 'Pregunta frecuente creada correctamente.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $this->validateFaq($request);
        $data['is_active'] = $request->boolean('is_active');
        $this->shiftOrderUp(Faq::class, (int) $data['order'], $faq->id);
        $faq->update($data);
        return redirect()->route('admin.faqs.index')->with('success', 'Pregunta actualizada correctamente.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'Pregunta eliminada.');
    }

    private function validateFaq(Request $request): array
    {
        return $request->validate([
            'question' => 'required|string|max:500',
            'answer'   => 'required|string',
            'category' => 'required|string|max:100',
            'order'    => 'nullable|integer',
        ]);
    }
}
