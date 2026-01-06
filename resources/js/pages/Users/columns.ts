import DataTableCellActions from '@/components/ui/datatable/DataTableCellActions.vue';
import users from '@/routes/users';
import { User } from '@/types/models/User';
import { router } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { Edit, Trash2 } from 'lucide-vue-next';
import { h } from 'vue';

export const columns: ColumnDef<User>[] = [
    {
        accessorKey: 'id',
        header: () => h('div', { class: 'text-center font-bold' }, 'Id'),
        cell: ({ row }) =>
            h('div', { class: 'text-center' }, row.getValue('id')),
    },
    {
        accessorKey: 'name',
        header: () => h('div', { class: ' font-bold' }, 'Nombre'),
        cell: ({ row }) => h('div', { class: '' }, row.getValue('name')),
    },
    {
        accessorKey: 'email',
        header: () => h('div', { class: ' font-bold' }, 'Correo'),
        cell: ({ row }) => h('div', { class: '' }, row.getValue('email')),
    },

    {
        id: 'actions',
        cell: ({ row }) => {
            const user = row.original;

            return h(DataTableCellActions, {
                title: 'Acciones',
                actions: [
                    {
                        label: 'Editar',
                        icon: Edit,
                        href: users.edit(user.id).url,
                    },
                    {
                        label: 'Eliminar',
                        icon: Trash2,
                        requiresConfirmation: true,
                        callback: () => {
                            (router.delete(users.destroy(user.id)),
                                {
                                    preserveScroll: true,
                                    onSuccess: () =>
                                        console.log('borrado con exito'),
                                });
                        },
                    },
                ],
            });
        },
    },
];
