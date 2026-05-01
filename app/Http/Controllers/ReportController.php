<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Mostrar formulario de reporte
     */
    public function create()
    {
        return view('report');
    }

    /**
     * Guardar un nuevo reporte
     */
    public function store(Request $request)
    {
        $request->validate([
            'reportable_type' => 'required|in:App\Models\Event,App\Models\User',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|min:10|max:500',
        ]);

        // Verificar que el elemento existe
        $modelClass = $request->reportable_type;
        $reportable = $modelClass::find($request->reportable_id);

        if (!$reportable) {
            return back()->withErrors(['reportable_id' => 'El elemento que intentas reportar no existe.']);
        }

        // Verificar que el usuario no reporte su propio contenido
        if ($request->reportable_type === 'App\Models\User' && $request->reportable_id == Auth::id()) {
            return back()->withErrors(['reportable_id' => 'No puedes reportar tu propio perfil.']);
        }

        if ($request->reportable_type === 'App\Models\Event' && $reportable->user_id == Auth::id()) {
            return back()->withErrors(['reportable_id' => 'No puedes reportar tu propio evento.']);
        }

        // Verificar si ya existe un reporte del mismo usuario para el mismo elemento
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reportable_type', $request->reportable_type)
            ->where('reportable_id', $request->reportable_id)
            ->first();

        if ($existingReport) {
            return back()->withErrors(['reason' => 'Ya has reportado este elemento anteriormente.']);
        }

        // Crear el reporte
        Report::create([
            'reporter_id' => Auth::id(),
            'reportable_type' => $request->reportable_type,
            'reportable_id' => $request->reportable_id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Tu reporte ha sido enviado y será revisado por el equipo de moderación.');
    }
}
