<template>
    <div id="dtContainer">
        <div id="dtTopContainer" class="flex flex-col">
            <div id="dtActionContainer">
                <button>Add</button>
                <button>Edit</button>
                <button>Delete</button>
                <button>Export</button>
                <button>Import</button>
                <button>Select All</button>
                <button>Deselect</button>
                {{response['meta']['from']}}
            </div>
            <div id="dtFilterContainer" class="flex justify-between">
                <select>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50" selected>50</option>
                    <option value="100">100</option>
                </select>
                <input type="text" id="dtSearch" placeholder="Search" />
            </div>
        </div>
        <div id="dtTableContainer">
            <table id="dtTable">
                <thead id="dtHeader">
                <tr class="dtHeaderRow">
                    <th class="dtHeaderColumn" v-for="column in columns" :key="column">
                        <div class="dtHeaderCell">
                                <span class="dtHeaderCellText">
                                    {{ column }}
                                </span>
                            <span class="dtHeaderCellSortIcon asc"></span>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody id="dtBody">
                <tr id="dtRowProcessing" v-if="false">
                    Processing
                </tr>
                <tr class="dtBodyRow" v-for="row in response['data']">
                    <td class="dtBodyCell" v-for="cell in row" :key="cell">
                        {{ cell }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="dtFooterContainer" class="flex justify-between">
            <div id="dtPageDetails">
                Showing {{ response['meta']['from'] }} to {{ response['meta']['to'] }} of {{ response['meta']['total'] }} entries
            </div>
            <div id="dtPageBtns">

            </div>
            <div id="dtPaginatorContainer">
                <a :href="response['links']['first']">First</a>
                <a :href="response['links']['prev']">Prev</a>
                <a :href="response['links']['next']">Next</a>
                <a :href="response['links']['last']">Last</a>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";

const columns = ['id', 'name', 'email', 'created_at', 'updated_at'];
const link = route('account.for.accounts', 1);
const response = ref([]);

class Datatable {
    constructor(columns, link) {
        this.columns = columns;
        this.link = link;
    }

    async getData() {
        try {
            const response = await axios.get(this.link);
            return response.data;
        } catch (error) {
            console.error(error);
            return null;
        }
    }
}

onMounted(async () => {
    const dt = new Datatable(columns, link);
    response.value = await dt.getData();
});
</script>

<style>
.asc::after {
    content: '▲';
}

.desc::after {
    content: '▼';
}
</style>
