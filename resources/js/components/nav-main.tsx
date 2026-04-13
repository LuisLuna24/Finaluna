import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';
import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import { navigation, type NavNode, type NavLeaf, type NavGroup } from '@/config/navigation';

// ─── Utilidad: detectar ruta activa ──────────────────────────────────────────

function useIsActive(href: string): boolean {
    const { url } = usePage();
    // Activo si la URL actual empieza con el href (para sub-rutas)
    return url === href || url.startsWith(href + '/');
}

function isGroupActive(group: NavGroup, currentUrl: string): boolean {
    return group.children.some(
        (child) => currentUrl === child.href || currentUrl.startsWith(child.href + '/')
    );
}

// ─── Ítem simple ─────────────────────────────────────────────────────────────

function NavItemSingle({ item }: { item: NavLeaf }) {
    const active = useIsActive(item.href);
    const Icon = item.icon;

    return (
        <SidebarMenuItem>
            <SidebarMenuButton asChild isActive={active} tooltip={item.title}>
                <Link href={item.href} className="flex items-center gap-2">
                    {Icon && <Icon className="size-4 shrink-0" />}
                    <span className="truncate">{item.title}</span>
                    {item.badge !== undefined && (
                        <span className={cn(
                            'ml-auto rounded-full px-1.5 py-0.5 text-[10px] font-semibold leading-none',
                            typeof item.badge === 'number'
                                ? 'bg-primary text-primary-foreground'
                                : 'bg-amber-500 text-white'
                        )}>
                            {item.badge}
                        </span>
                    )}
                </Link>
            </SidebarMenuButton>
        </SidebarMenuItem>
    );
}

// ─── Grupo con acordeón ───────────────────────────────────────────────────────

function NavItemGroup({ item }: { item: NavGroup }) {
    const { url } = usePage();
    const groupActive = isGroupActive(item, url);
    const [open, setOpen] = useState(item.defaultOpen ?? groupActive);
    const Icon = item.icon;

    return (
        <Collapsible open={open} onOpenChange={setOpen} asChild>
            <SidebarMenuItem>
                {/* Trigger del grupo */}
                <CollapsibleTrigger asChild>
                    <SidebarMenuButton
                        tooltip={item.title}
                        isActive={groupActive}
                        className="w-full"
                    >
                        {Icon && <Icon className="size-4 shrink-0" />}
                        <span className="truncate">{item.title}</span>
                        <ChevronRight
                            className={cn(
                                'ml-auto size-4 shrink-0 text-muted-foreground transition-transform duration-200',
                                open && 'rotate-90'
                            )}
                        />
                    </SidebarMenuButton>
                </CollapsibleTrigger>

                {/* Sub-ítems */}
                <CollapsibleContent>
                    <SidebarMenuSub>
                        {item.children.map((child) => {
                            const childActive =
                                url === child.href || url.startsWith(child.href + '/');
                            const ChildIcon = child.icon;

                            return (
                                <SidebarMenuSubItem key={child.href}>
                                    <SidebarMenuSubButton asChild isActive={childActive}>
                                        <Link href={child.href} className="flex items-center gap-2">
                                            {ChildIcon && (
                                                <ChildIcon className="size-3.5 shrink-0 text-muted-foreground" />
                                            )}
                                            <span className="truncate">{child.title}</span>
                                            {child.badge !== undefined && (
                                                <span className={cn(
                                                    'ml-auto rounded-full px-1.5 py-0.5 text-[10px] font-semibold leading-none',
                                                    typeof child.badge === 'number'
                                                        ? 'bg-primary text-primary-foreground'
                                                        : 'bg-amber-500 text-white'
                                                )}>
                                                    {child.badge}
                                                </span>
                                            )}
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            );
                        })}
                    </SidebarMenuSub>
                </CollapsibleContent>
            </SidebarMenuItem>
        </Collapsible>
    );
}

// ─── Renderizador principal ───────────────────────────────────────────────────

function renderNode(node: NavNode, index: number) {
    if (node.type === 'divider') {
        return (
            <SidebarGroup key={`divider-${index}`} className="pt-4 first:pt-0">
                {node.label && (
                    <SidebarGroupLabel className="px-2 text-[11px] uppercase tracking-widest text-muted-foreground/60">
                        {node.label}
                    </SidebarGroupLabel>
                )}
            </SidebarGroup>
        );
    }

    return null; // Los items y grupos se manejan en NavMain
}

// ─── Componente exportable ────────────────────────────────────────────────────

export function NavMain() {
    // Agrupar nodos entre divisores para renderizado semántico
    const sections: { divider?: NavNode & { type: 'divider' }; items: NavNode[] }[] = [];
    let current: (typeof sections)[number] = { items: [] };

    for (const node of navigation) {
        if (node.type === 'divider') {
            if (current.items.length > 0 || current.divider) {
                sections.push(current);
            }
            current = { divider: node, items: [] };
        } else {
            current.items.push(node);
        }
    }
    if (current.items.length > 0 || current.divider) {
        sections.push(current);
    }

    return (
        <>
            {sections.map((section, sIdx) => (
                <SidebarGroup key={sIdx}>
                    {section.divider?.label && (
                        <SidebarGroupLabel className="px-2 text-[10px] font-semibold uppercase tracking-widest text-muted-foreground/50">
                            {section.divider.label}
                        </SidebarGroupLabel>
                    )}
                    <SidebarGroupContent>
                        <SidebarMenu>
                            {section.items.map((node, nIdx) => {
                                if (node.type === 'item') {
                                    return <NavItemSingle key={node.href} item={node} />;
                                }
                                if (node.type === 'group') {
                                    return <NavItemGroup key={`${node.title}-${nIdx}`} item={node} />;
                                }
                                return null;
                            })}
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>
            ))}
        </>
    );
}