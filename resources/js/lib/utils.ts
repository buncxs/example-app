import { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(
    urlToCheck: NonNullable<InertiaLinkProps['href']>,
    currentUrl: string,
) {
    return toUrl(urlToCheck) === currentUrl;
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

// resources/js/lib/utils.ts

import type { Updater } from '@tanstack/vue-table'
import type { Ref } from 'vue'

// Esta es la función que te falta para que las tablas funcionen bien
export function valueUpdater<T>(updaterOrValue: Updater<T>, ref: Ref<T>) {
  if (typeof updaterOrValue === 'function') {
    ref.value = (updaterOrValue as (prev: T) => T)(ref.value)
  } else {
    ref.value = updaterOrValue
  }
}
