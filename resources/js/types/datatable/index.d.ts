import '@tanstack/vue-table';

declare module '@tanstack/vue-table' {
    interface TableMeta<TData extends RowData> {
        current_page?: number;
        per_page?: number;
    }
}