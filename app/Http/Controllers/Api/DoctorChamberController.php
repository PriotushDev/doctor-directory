<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorChamber;
use App\Http\Requests\StoreDoctorChamberRequest;
use App\Http\Requests\UpdateDoctorChamberRequest;
use Illuminate\Http\Request;

class DoctorChamberController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    } 

    public function index(Request $request)
    {
        $query = DoctorChamber::with(['doctor', 'hospital']);

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $chambers = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $chambers
        ]);
    }

    private function getDoctorIdForUser($user) {
        if (!$user) return null;
        $doc = \App\Models\Doctor::where('user_id', $user->id)->orWhere('email', $user->email)->first();
        return $doc ? $doc->id : null;
    }

    public function store(StoreDoctorChamberRequest $request)
    {
        $user = auth()->user();
        if ($user && !$user->hasRole('admin') && !$user->hasRole('manager')) {
            // If doctor, enforce they create for themselves (implicitly allowing the action)
            if ($user->hasRole('doctor')) {
                $myDocId = $this->getDoctorIdForUser($user);
                if ((int)$request->doctor_id !== (int)$myDocId) {
                    return response()->json(['message' => 'You can only create chambers for yourself.'], 403);
                }
            } else if (!$user->hasPermissionTo('doctor_chamber.create')) {
                abort(403, 'Unauthorized');
            }
        }

        $chamber = DoctorChamber::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Doctor chamber created successfully',
            'data' => $chamber
        ]);
    }

    public function show($id)
    {
        $chamber = DoctorChamber::with(['doctor', 'hospital'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $chamber
        ]);
    }

    public function update(UpdateDoctorChamberRequest $request, $id)
    {
        $user = auth()->user();
        $chamber = DoctorChamber::findOrFail($id);

        if ($user && !$user->hasRole('admin') && !$user->hasRole('manager')) {
            if ($user->hasRole('doctor')) {
                $myDocId = $this->getDoctorIdForUser($user);
                // Cannot update someone else's chamber
                if ((int)$chamber->doctor_id !== (int)$myDocId) {
                    return response()->json(['message' => 'You can only update your own chambers.'], 403);
                }
                // Cannot reassign to another doctor
                if ((int)$request->doctor_id !== (int)$myDocId) {
                    return response()->json(['message' => 'You cannot assign this chamber to another doctor.'], 403);
                }
            } else if (!$user->hasPermissionTo('doctor_chamber.update')) {
                abort(403, 'Unauthorized');
            }
        }

        $chamber->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Doctor chamber updated successfully',
            'data' => $chamber
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $chamber = DoctorChamber::findOrFail($id);

        if ($user && !$user->hasRole('admin') && !$user->hasRole('manager')) {
            // Give doctors explicit ability to delete own chambers EVEN IF they lack global delete perm
            if ($user->hasRole('doctor')) {
                $myDocId = $this->getDoctorIdForUser($user);
                if ((int)$chamber->doctor_id !== (int)$myDocId) {
                    return response()->json(['message' => 'You can only delete your own chambers.'], 403);
                }
            } else if (!$user->hasPermissionTo('doctor_chamber.delete')) {
                abort(403, 'Unauthorized');
            }
        }

        $chamber->delete();

        return response()->json([
            'success' => true,
            'message' => 'Doctor chamber deleted successfully'
        ]);
    }
}
