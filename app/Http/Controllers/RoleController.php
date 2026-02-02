<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{

    public function __construct(protected RoleService $roleService){}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:roles.view', only: ['index']),
            new Middleware('permission:roles.create', only: ['create', 'store']),
            new Middleware('permission:roles.edit', only: ['edit', 'update']),
            new Middleware('permission:roles.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : Response
    {
        $roles = Role::select('id', 'name', 'description', 'icon')
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
    public function create() : Response
    {
        return Inertia::render('Roles/Create', [
            'permissions' => $this->roleService->getGroupedPermissions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $this->roleService->createRole($request->validated());
            return to_route('roles.index')->with('success', 'Rol creado con exito');
        } catch (\Exception $e) {
            return back()
                ->with(['error' => 'No se pudo crear el rol: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Role $role) : Response
    {
        return Inertia::render('Roles/Show', [
            'role' => RoleResource::make($role->load('permissions:id,name,module')),
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role) : Response
    {
        return Inertia::render('Roles/Edit', [
            'role' => RoleResource::make($role->load('permissions:id')),
            'permissions' => $this->roleService->getGroupedPermissions(),
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $this->roleService->updateRole($role, $request->validated());
            return to_route('roles.index')
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
        return back()->with('success', 'Rol eliminado correctamente');
    }
}
