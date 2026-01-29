import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";


export function useAuth(){

    const page = usePage();

    const user = computed(() => page.props.auth.user);
    const roles = computed(() => page.props.auth.roles ?? []);
    const permissions = computed(() => page.props.auth.permissions ?? []);

    // Helper permisos
    const can = (permission: string ) => {
        if(roles.value.includes('Super Administrador')) return true;
        return permissions.value.includes(permission);
    }

    // Helper roles
    const is = (role: string) => roles.value.includes(role);

    return {
        user, roles, permissions, can, is
    }


}