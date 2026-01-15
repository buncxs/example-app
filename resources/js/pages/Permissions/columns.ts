import DataTableCellActions from '@/components/ui/datatable/DataTableCellActions.vue';
import permissions from '@/routes/permissions';
import { Permission } from '@/types/models/Permission';
import { router } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { Edit, Trash2 } from 'lucide-vue-next';
import { h } from 'vue';

export const columns: ColumnDef<Permission>[] = [
    {
        id: 'index',
        header: () => h('div', { class: 'text-center font-bold' }, 'No'),
        cell: ({ row }) => h('div', { class: 'text-center' }, row.index + 1),
        size: 10,
    },
    {
        accessorKey: 'name',
        header: () => h('div', { class: ' font-bold' }, 'Nombre'),
        cell: ({ row }) => h('div', { class: '' }, row.getValue('name')),
    },
    {
        id: 'actions',
        cell: ({ row }) => {
            const permission = row.original;

            return h('div', { class: 'flex justify-center' }, [
                h(DataTableCellActions, {
                    title: 'Acciones',
                    actions: [
                        {
                            label: 'Editar',
                            icon: Edit,
                            href: permissions.edit(permission.uuid).url,
                        },
                        {
                            label: 'Eliminar',
                            icon: Trash2,
                            requiresConfirmation: true,
                            callback: () => {
                                router.delete(permissions.destroy(permission.uuid), {
                                    preserveScroll: true,
                                });
                            },
                        },
                    ],
                }),
            ]);
        },
        size: 40,
    },
];
