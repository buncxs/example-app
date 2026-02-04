import DataTableCellActions from '@/components/ui/datatable/DataTableCellActions.vue';
import roles from '@/routes/roles';
import { Role } from '@/types/models/Role';
import { Link, router } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { Edit, Trash2 } from 'lucide-vue-next';
import { h } from 'vue';

export const getColumns = (can: (p: string)=> boolean): ColumnDef<Role>[] => [
    {
        id: 'index',
        header: () => h('div', { class: 'text-center font-bold' }, 'No'),
        cell: ({ row, table }) =>{
            const meta = table.options.meta;
            const page = meta?.current_page ?? 1;
            const perPage = meta?.per_page ?? 10;
        
            const continuousIndex = (page - 1) * perPage + (row.index + 1);
            return h('div', { class: 'text-center' }, continuousIndex);
            
        },
        size: 10,
    },
    {
        accessorKey: 'name',
        header: () => h('div', { class: ' font-bold' }, 'Nombre'),
        cell: ({ row }) => {
            return h(
                Link,
                {
                    href: `/roles/${row.original.id}`,
                    class: 'inline-block transition-all duration-200 ease-out hover:text-blue-600 active:scale-95'
                },
                () => row.getValue('name')
            );
        },
    },
    {
        accessorKey: 'description',
        header: () => h('div', { class: ' font-bold text-left' }, 'Descripción'),
        cell: ({ row }) => h('div', { class: '' }, row.getValue('description')),
    },
    {
        id: 'actions',
        cell: ({ row }) => {
            const role = row.original;
            const actions = [];
            
            if(can('roles.edit')){
                actions.push(
                    {
                        label: 'Editar',
                        icon: Edit,
                        href: roles.edit(role.id).url,
                    },
                );
            }
            if(can('roles.delete')){
                actions.push(
                    {
                        label: 'Eliminar',
                        icon: Trash2,
                        requiresConfirmation: true,
                        callback: () => {
                            router.delete(roles.destroy(role.id), {
                                preserveScroll: true,
                            });
                        },
                    },
                ); 
            }

            return h('div', { class: 'flex justify-center' }, [
                h(DataTableCellActions, {
                    title: 'Acciones',
                    actions: actions,
                }),
            ]);
        },
        size: 40,
    },
];
