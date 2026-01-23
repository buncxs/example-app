<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct(protected RoleService $roleService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::select('id', 'name')
            ->with('permissions:id,name')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)->withQueryString();

        return Inertia::render('Roles/Index', [
            'items' => RoleResource::collection($roles),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissionsSql = Permission::select('id', 'name', 'module')->get();

        $permissions = collect(PermissionResource::collection($permissionsSql)
            ->resolve())
            ->groupBy('module');

        return Inertia::render('Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $this->roleService->createRole($request->validated());
            return redirect()->route('roles.index')->with('success', 'Rol creado con exito');
        } catch (\Exception $e) {
            return back()
                ->with(['error' => 'No se pudo crear el rol: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role->load('permissions:id,name');

        $permissions = Permission::select('id', 'name', 'module')->get()
            ->pipe(fn($p) => PermissionResource::collection($p))
            ->resolve();

        $permissions = collect($permissions)->groupBy('module');

        return Inertia::render('Roles/Edit', [
            'role' => RoleResource::make($role),
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $this->roleService->updateRole($role, $request->validated());
            return redirect()->route('roles.index')
                ->with('success', "El rol {$role->name} ha sido actualizado");
        } catch (\Exception $e) {
            return back()
                ->with(['error' => 'No se pudo actualizar el rol: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Rol eliminado correctamente');
    }
}
