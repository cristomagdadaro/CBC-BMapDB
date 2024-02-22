import {Category} from "@/Pages/Services/InventorySys/domain/Category.js";
import {Item} from "@/Pages/Services/InventorySys/domain/Item.js";
import {Personnel} from "@/Pages/Services/InventorySys/domain/Personnel.js";
import {Supplier} from "@/Pages/Services/InventorySys/domain/Supplier.js";
import {Transaction} from "@/Pages/Services/InventorySys/domain/Transaction.js";

import { defineAsyncComponent } from "vue";
import {Stock} from "@/Pages/Services/InventorySys/domain/Stock.js";

export const InventorySysPages = {
    api: {
        category: {
            path: route('api.inventory.categories.index'),
            name: 'Categories Model',
            model: Category,
            create: null,
            edit: null,
        },
        item: {
            path: route('api.inventory.items.index'),
            name: 'Items Model',
            model: Item,
            create: null,
            edit: null,
        },
        supplier: {
            path: route('api.inventory.suppliers.index'),
            name: 'Suppliers Model',
            model: Supplier,
            create: null,
            edit: null,
        },
        transaction: {
            path: route('api.inventory.transactions.index'),
            name: 'Transactions Model',
            model: Transaction,
            create: null,
            edit: null,
        },
        stock: {
            path: route('api.inventory.stocks.index'),
            name: 'Stocks Model',
            model: Stock,
            create: null,
            edit: null,
        },
    },
    index: {
        path: route('services.inventory.index'),
        name: 'Inventory System',
        component: null,
    },
}
