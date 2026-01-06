<script setup lang="ts" generic="TData, TValue">
import { valueUpdater } from '@/lib/utils';
import type {
    ColumnDef,
    ColumnFiltersState,
    ExpandedState,
    SortingState,
    VisibilityState,
} from '@tanstack/vue-table';
import { ref } from 'vue';

import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    useVueTable,
} from '@tanstack/vue-table';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

const props = defineProps<{
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
}>();

const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});
const expanded = ref<ExpandedState>({});

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnFilters),
    onColumnVisibilityChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnVisibility),
    onRowSelectionChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, rowSelection),
    onExpandedChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, expanded),
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
        get expanded() {
            return expanded.value;
        },
        pagination: { pageIndex: 0, pageSize: 10 },
    },
});

defineExpose({ table })

</script>

<template>   
    <div class="overflow-x-auto rounded-lg bg-white shadow-md">
        <Table>
            <TableHeader class="bg-black [&>tr:hover]:bg-inherit">
                <TableRow
                    v-for="headerGroup in table.getHeaderGroups()"
                    :key="headerGroup.id"
                >
                    <TableHead
                        v-for="header in headerGroup.headers"
                        :key="header.id"
                        class="px-6 py-2 text-xs font-medium tracking-wider text-white uppercase"
                    >
                        <FlexRender
                            v-if="!header.isPlaceholder"
                            :render="header.column.columnDef.header"
                            :props="header.getContext()"
                        />
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody
                class="divide-y divide-gray-200 bg-white [&>tr:hover]:bg-yellow-100"
            >
                <template v-if="table.getRowModel().rows?.length">
                    <TableRow
                        v-for="row in table.getRowModel().rows"
                        :key="row.id"
                        :data-state="
                            row.getIsSelected() ? 'selected' : undefined
                        "
                        class="transition-colors odd:bg-white even:bg-gray-100"
                    >
                        <TableCell
                            v-for="cell in row.getVisibleCells()"
                            :key="cell.id"
                            class="px-6 py-2 text-sm whitespace-nowrap text-gray-800"
                        >
                            <FlexRender
                                :render="cell.column.columnDef.cell"
                                :props="cell.getContext()"
                            />
                        </TableCell>
                    </TableRow>
                </template>
                <template v-else>
                    <TableRow>
                        <TableCell :colspan="columns.length" class="h-24">
                            No results.
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
        <!-- Controles de paginación -->
        <div
            v-if="table.getPageCount() > 1"
            class="flex items-center justify-between border-t bg-gray-50 px-4 py-2"
        >
            <div class="flex gap-2">
                <button
                    class="rounded bg-gray-200 px-3 py-1 text-sm disabled:opacity-50"
                    :disabled="!table.getCanPreviousPage()"
                    @click="table.previousPage()"
                >
                    Anterior
                </button>
                <button
                    class="rounded bg-gray-200 px-3 py-1 text-sm disabled:opacity-50"
                    :disabled="!table.getCanNextPage()"
                    @click="table.nextPage()"
                >
                    Siguiente
                </button>
            </div>
            <span class="text-sm text-gray-600">
                Página {{ table.getState().pagination.pageIndex + 1 }} de
                {{ table.getPageCount() }}
            </span>
        </div>
    </div>
</template>
