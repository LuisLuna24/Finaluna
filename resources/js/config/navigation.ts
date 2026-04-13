import {
    LayoutDashboard,
    BookOpen,
    Package,
    Tags,
    Truck,
    Warehouse,
    Users,
    ShieldCheck,
    BarChart2,
    FileText,
    TrendingUp,
    Settings,
    Bell,
    Palette,
    type LucideIcon,
} from 'lucide-react';

export interface NavLeaf {
    type: 'item';
    title: string;
    href: string;
    icon?: LucideIcon;
    badge?: string | number;        // ej: "Nuevo", 5 (contador)
    permission?: string;            // ej: 'catalogos.productos.view'
}

export interface NavGroup {
    type: 'group';
    title: string;
    icon?: LucideIcon;
    permission?: string;            // si no tiene permiso, oculta todo el grupo
    defaultOpen?: boolean;
    children: NavLeaf[];
}

export interface NavDivider {
    type: 'divider';
    label?: string;                 // etiqueta de sección, opcional
}

export type NavNode = NavLeaf | NavGroup | NavDivider;

// ─── DEFINICIÓN COMPLETA DE NAVEGACIÓN ──────────────────────────────────────

export const navigation: NavNode[] = [
    // ── Principal ─────────────────────────────────────────────────────────
    {
        type: 'item',
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutDashboard,
    },
    {
        type: 'item',
        title: 'Familia',
        href: '/dashboard',
        icon: Users,
    },
    {
        type: 'group',
        title: 'Catalogos',
        icon: Warehouse,
        defaultOpen: false,
        permission: 'inventario.view',
        children: [
            { type: 'item', title: 'Categorias',    href: '/catalogs/categories',   icon: Package },
            { type: 'item', title: 'Metodos de pago',   href: '/catalogs/payment-methods',  icon: Tags },
            { type: 'item', title: 'Tipos de gastos',  href: '/catalogs/expenses', icon: Truck },
        ],
    },
    {
        type: 'item',
        title: 'Presupuestos',
        href: '/dashboard',
        icon: FileText,
    },
    {
        type: 'item',
        title: 'Ahorros',
        href: '/dashboard',
        icon: TrendingUp,
    },
];