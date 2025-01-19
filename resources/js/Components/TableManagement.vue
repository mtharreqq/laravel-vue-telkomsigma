<script setup lang="ts">
import DebouncedInput from '@/Components/DebouncedInput.vue';
import Filter from '@/Components/Filter.vue';
import {
    ColumnFiltersState,
    FlexRender,
    createColumnHelper,
    getCoreRowModel,
    getFacetedMinMaxValues,
    getFacetedRowModel,
    getFacetedUniqueValues,
    getFilteredRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ref } from 'vue';

type Person = {
    id: number;
    name: string;
    gender: 'Male' | 'Female';
    email: string;
    dob: string;
    phone: string;
    country: string;
    updated_at: string;
};

const props = defineProps({
    persons: Array,
    deletePerson: Function,
});
const persons = props.persons! as Person[];
const defaultData: Person[] = persons;
const columnHelper = createColumnHelper<Person>();
const columns = [
    columnHelper.accessor('name', {
        header: () => 'Name',
    }),
    columnHelper.accessor('gender', {
        header: () => 'Gender',
    }),
    columnHelper.accessor('email', {
        header: () => 'Email',
    }),
    // columnHelper.accessor('dob', {
    //     header: () => 'Date of Birth',
    //     cell: (info) => {
    //         const dateString = info.getValue(); // Get the string value
    //         const formattedDate = new Date(dateString)
    //             .toLocaleDateString('en-GB', {
    //                 day: '2-digit',
    //                 month: 'long',
    //                 year: 'numeric',
    //             })
    //             .replace(',', ''); // Format the date
    //         return formattedDate;
    //     },
    // }),
    columnHelper.accessor('phone', {
        header: () => 'Phone',
    }),
    columnHelper.accessor('country', {
        header: () => 'Country',
    }),
    columnHelper.accessor('updated_at', {
        header: () => 'Last Update',
        cell: (info) => {
            const dateString = info.getValue(); // Get the string value
            const formattedDate = new Date(dateString)
                .toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                })
                .replace(',', ''); // Format the date
            return formattedDate;
        },
    }),

    // {
    //     id: 'select',
    //     header: ({ table }: { table: any }) => {
    //         return (
    //             <IndeterminateCheckbox
    //                 checked={table.getIsAllRowsSelected()}
    //                 indeterminate={table.getIsSomeRowsSelected()}
    //                 onChange={table.getToggleAllRowsSelectedHandler()}
    //             ></IndeterminateCheckbox>
    //         );
    //     },
    //     cell: ({ row }: { row: any }) => {
    //         return (
    //             <IndeterminateCheckbox
    //                 checked={row.getIsSelected()}
    //                 disabled={!row.getCanSelect()}
    //                 onChange={row.getToggleSelectedHandler()}
    //             ></IndeterminateCheckbox>
    //         );
    //     },
    // },
];
const data = ref(defaultData);
// const rerender = () => {
//     data.value = defaultData;
// };
const columnFilters = ref<ColumnFiltersState>([]);
const globalFilter = ref('');

const table = useVueTable({
    get data() {
        return data.value;
    },
    columns,
    state: {
        get columnFilters() {
            return columnFilters.value;
        },
        get globalFilter() {
            return globalFilter.value;
        },
    },
    onColumnFiltersChange: (updaterOrValue) => {
        columnFilters.value =
            typeof updaterOrValue === 'function'
                ? updaterOrValue(columnFilters.value)
                : updaterOrValue;
    },
    onGlobalFilterChange: (updaterOrValue) => {
        globalFilter.value =
            typeof updaterOrValue === 'function'
                ? updaterOrValue(globalFilter.value)
                : updaterOrValue;
    },
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    getFacetedMinMaxValues: getFacetedMinMaxValues(),
});
</script>

<template>
    <div class="p-2">
        <div class="flex space-x-2">
            <!-- <DebouncedInput
                :modelValue="globalFilter ?? ''"
                @update:modelValue="(value) => (globalFilter = String(value))"
                className="p-2 font-lg shadow border border-block"
                placeholder="Search all columns..."
            /> -->

            <!-- Spesific separate filter -->
            <!-- <template
                v-if="
                    !table.getHeaderGroups()[0].headers[0].isPlaceholder &&
                    table.getHeaderGroups()[0].headers[0].column.getCanFilter()
                "
            >
                <Filter
                    :column="table.getHeaderGroups()[0].headers[0].column"
                    :table="table"
                />
            </template> -->
        </div>
        <div
            class="flex-column flex flex-wrap items-center justify-between space-y-4 pb-4 sm:flex-row sm:space-y-0"
        >
            <!-- <div>
                <button
                    id="dropdownRadioButton"
                    data-dropdown-toggle="dropdownRadio"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                    type="button"
                >
                    <svg
                        class="me-3 h-3 w-3 text-gray-500 dark:text-gray-400"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"
                        />
                    </svg>
                    Last 30 days
                    <svg
                        class="ms-2.5 h-2.5 w-2.5"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 10 6"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 1 4 4 4-4"
                        />
                    </svg>
                </button>
                <div
                    id="dropdownRadio"
                    class="z-10 hidden w-48 divide-y divide-gray-100 rounded-lg bg-white shadow dark:divide-gray-600 dark:bg-gray-700"
                    data-popper-reference-hidden=""
                    data-popper-escaped=""
                    data-popper-placement="top"
                    style="
                        position: absolute;
                        inset: auto auto 0px 0px;
                        margin: 0px;
                        transform: translate3d(522.5px, 3847.5px, 0px);
                    "
                >
                    <ul
                        class="space-y-1 p-3 text-sm text-gray-700 dark:text-gray-200"
                        aria-labelledby="dropdownRadioButton"
                    >
                        <li>
                            <div
                                class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600"
                            >
                                <input
                                    id="filter-radio-example-1"
                                    type="radio"
                                    value=""
                                    name="filter-radio"
                                    class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                />
                                <label
                                    for="filter-radio-example-1"
                                    class="ms-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300"
                                    >Last day</label
                                >
                            </div>
                        </li>
                        <li>
                            <div
                                class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600"
                            >
                                <input
                                    checked=""
                                    id="filter-radio-example-2"
                                    type="radio"
                                    value=""
                                    name="filter-radio"
                                    class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                />
                                <label
                                    for="filter-radio-example-2"
                                    class="ms-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300"
                                    >Last 7 days</label
                                >
                            </div>
                        </li>
                        <li>
                            <div
                                class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600"
                            >
                                <input
                                    id="filter-radio-example-3"
                                    type="radio"
                                    value=""
                                    name="filter-radio"
                                    class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                />
                                <label
                                    for="filter-radio-example-3"
                                    class="ms-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300"
                                    >Last 30 days</label
                                >
                            </div>
                        </li>
                        <li>
                            <div
                                class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600"
                            >
                                <input
                                    id="filter-radio-example-4"
                                    type="radio"
                                    value=""
                                    name="filter-radio"
                                    class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                />
                                <label
                                    for="filter-radio-example-4"
                                    class="ms-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300"
                                    >Last month</label
                                >
                            </div>
                        </li>
                        <li>
                            <div
                                class="flex items-center rounded p-2 hover:bg-gray-100 dark:hover:bg-gray-600"
                            >
                                <input
                                    id="filter-radio-example-5"
                                    type="radio"
                                    value=""
                                    name="filter-radio"
                                    class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                />
                                <label
                                    for="filter-radio-example-5"
                                    class="ms-2 w-full rounded text-sm font-medium text-gray-900 dark:text-gray-300"
                                    >Last year</label
                                >
                            </div>
                        </li>
                    </ul>
                </div>
            </div> -->
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative">
                <div
                    class="rtl:inset-r-0 pointer-events-none absolute inset-y-0 left-0 flex items-center ps-3 rtl:right-0"
                >
                    <svg
                        class="h-5 w-5 text-gray-500 dark:text-gray-400"
                        aria-hidden="true"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </div>
                <DebouncedInput
                    :modelValue="globalFilter ?? ''"
                    @update:modelValue="
                        (value) => (globalFilter = String(value))
                    "
                    className="block w-80 rounded-lg border border-gray-300 bg-gray-50 p-2 ps-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                    placeholder="Search all columns..."
                />
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table
                class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400"
            >
                <thead
                    class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400"
                >
                    <tr
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <th
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                            :colSpan="header.colSpan"
                            scope="col"
                            class="px-6 py-3"
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                            <template
                                v-if="
                                    !header.isPlaceholder &&
                                    header.column.getCanFilter()
                                "
                            >
                                <Filter
                                    :column="header.column"
                                    :table="table"
                                />
                            </template>
                        </th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="row in table.getRowModel().rows"
                        :key="row.id"
                        class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600"
                    >
                        <td
                            v-for="(cell, index) in row.getVisibleCells()"
                            :key="cell.id"
                            :class="
                                index === 0
                                    ? 'whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white'
                                    : ''
                            "
                        >
                            <FlexRender
                                :render="cell.column.columnDef.cell"
                                :props="cell.getContext()"
                            />
                        </td>
                        <td class="space-x-3 px-6 py-4 text-right">
                            <a
                                :href="`/persons/${row.original.id}/edit`"
                                class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                >Edit</a
                            >
                            <a
                                @click="
                                    deletePerson &&
                                    deletePerson(
                                        row.original.id,
                                        row.original.name,
                                    )
                                "
                                class="font-medium text-red-600 hover:cursor-pointer hover:underline dark:text-red-500"
                                >Remove</a
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
