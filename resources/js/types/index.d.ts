import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface CartLine {
    id: number;
    quantity: number;
    unit_price: string;
    sub_total: string;
    product_name: string;
    variant_label: string;
    image: string;
}

export interface CartData {
    total: string;
    item_count: number;
    lines: CartLine[];
}

export interface ShippingOption {
    identifier: string;
    name: string;
    description: string | null;
    price: string;
    price_value: number;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    cart: CartData | null;
    step?: 'address' | 'shipping';
    shippingOptions?: ShippingOption[];
    countries?: { code: string; name: string }[];
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
