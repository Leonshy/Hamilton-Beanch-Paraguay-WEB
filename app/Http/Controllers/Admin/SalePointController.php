<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalePoint;
use App\Traits\HandlesOrder;
use Illuminate\Http\Request;

class SalePointController extends Controller
{
    use HandlesOrder;

    public function index()
    {
        $salePoints = SalePoint::with('logo')->orderBy('order')->get();
        return view('admin.sale-points.index', compact('salePoints'));
    }

    public function create()
    {
        $nextOrder = $this->nextOrder(SalePoint::class);
        return view('admin.sale-points.form', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $data = $this->validateSalePoint($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? $this->nextOrder(SalePoint::class);
        $this->shiftOrderUp(SalePoint::class, (int) $data['order']);
        SalePoint::create($data);
        return redirect()->route('admin.sale-points.index')->with('success', 'Punto de venta creado correctamente.');
    }

    public function edit(SalePoint $salePoint)
    {
        return view('admin.sale-points.form', compact('salePoint'));
    }

    public function update(Request $request, SalePoint $salePoint)
    {
        $data = $this->validateSalePoint($request);
        $data['is_active'] = $request->boolean('is_active');
        $this->shiftOrderUp(SalePoint::class, (int) $data['order'], $salePoint->id);
        $salePoint->update($data);
        return redirect()->route('admin.sale-points.index')->with('success', 'Punto de venta actualizado correctamente.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);
        $this->applyReorder(SalePoint::class, $data['ids']);
        return response()->json(['ok' => true]);
    }

    public function destroy(SalePoint $salePoint)
    {
        $salePoint->delete();
        return redirect()->route('admin.sale-points.index')->with('success', 'Punto de venta eliminado.');
    }

    private function validateSalePoint(Request $request): array
    {
        return $request->validate([
            'name'     => 'required|string|max:150',
            'url'      => 'nullable|url|max:500',
            'media_id' => 'nullable|exists:media,id',
            'order'    => 'nullable|integer',
        ]);
    }
}
