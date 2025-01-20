<script setup lang="ts">
import DebouncedInput from '@/Components/DebouncedInput.vue';
import Filter from '@/Components/Filter.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import toTitleCase from '@/lib/titleCase';
import {
    ColumnFiltersState,
    createColumnHelper,
    FlexRender,
    getCoreRowModel,
    getFacetedMinMaxValues,
    getFacetedRowModel,
    getFacetedUniqueValues,
    getFilteredRowModel,
    getSortedRowModel,
    SortingState,
    useVueTable,
} from '@tanstack/vue-table';
import { ref } from 'vue';
import PrimaryButton from './PrimaryButton.vue';

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
        cell: (info) => {
            return toTitleCase(info.getValue());
        },
    }),
    columnHelper.accessor('dob', {
        header: () => 'Date of Birth',
        cell: (info) => {
            const dateString = info.getValue();
            const formattedDate = new Date(dateString)
                .toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                })
                .replace(',', '');
            return formattedDate;
        },
    }),
    columnHelper.accessor('country', {
        header: () => 'Country',
    }),
    columnHelper.accessor('updated_at', {
        header: () => 'Last Update',
        cell: (info) => {
            const dateString = info.getValue();
            const formattedDate = new Date(dateString)
                .toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                })
                .replace(',', '');
            return formattedDate;
        },
    }),
];
const data = ref(defaultData);
const columnFilters = ref<ColumnFiltersState>([]);
const globalFilter = ref('');
const sorting = ref<SortingState>([]);
// const rerender = () => {
//     data.value = defaultData;
// };

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
        get sorting() {
            return sorting.value;
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
    onSortingChange: (updaterOrValue) => {
        sorting.value =
            typeof updaterOrValue === 'function'
                ? updaterOrValue(sorting.value)
                : updaterOrValue;
    },
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    getFacetedMinMaxValues: getFacetedMinMaxValues(),
    getSortedRowModel: getSortedRowModel(),
    // debugTable: true,
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
            <!-- <div
                    class="inline-block"
                    v-if="
                        !table.getHeaderGroups()[0].headers[1].isPlaceholder &&
                        table
                            .getHeaderGroups()[0]
                            .headers[1].column.getCanFilter()
                    "
                >
                    <Filter
                        :column="table.getHeaderGroups()[0].headers[1].column"
                        :table="table"
                    />
                </div> -->
            <caption
                class="w-full bg-white pb-5 text-left text-lg font-semibold text-gray-900 rtl:text-right dark:bg-gray-800 dark:text-white"
            >
                Generate Your Dummy Person
                <p
                    class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400"
                >
                    Manage and organize all your persons here. Create, update,
                    delete, and view records effortlessly.
                </p>
            </caption>
        </div>

        <div
            class="flex-column flex flex-wrap items-center justify-between space-y-4 pb-4 sm:flex-row sm:space-y-0"
        >
            <div class="space-x-3">
                <a href="persons/create">
                    <PrimaryButton
                        class="bg-blue-600 pl-7 hover:bg-blue-700 active:bg-blue-600"
                        ><svg
                            class="absolute -ms-[18px] mb-[1px] h-4 w-4"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        Add Person
                    </PrimaryButton>
                </a>
                <a href="persons/add/5">
                    <SecondaryButton> Generate 5 Person</SecondaryButton>
                </a>
                <a
                    href="persons/delete/all"
                    onclick="return confirm('Are you sure you want to delete all data? This action cannot be undone.');"
                >
                    <SecondaryButton
                        class="hover:border-red-800 hover:bg-red-50 hover:text-red-800"
                    >
                        Delete All</SecondaryButton
                    >
                </a>
            </div>
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
                            class="cursor-pointer px-6 py-3"
                        >
                            <div
                                v-if="
                                    !header.isPlaceholder &&
                                    header.column.getCanFilter()
                                "
                                class="flex justify-between gap-1"
                            >
                                <FlexRender
                                    v-if="!header.isPlaceholder"
                                    :render="header.column.columnDef.header"
                                    :props="header.getContext()"
                                />
                                {{
                                    { asc: ' 🔼', desc: ' 🔽' }[
                                        header.column.getIsSorted() as string
                                    ]
                                }}
                                <a
                                    class=""
                                    href="#"
                                    @click="
                                        header.column.getToggleSortingHandler()?.(
                                            $event,
                                        )
                                    "
                                    ><svg
                                        class="ms-1.5 h-3 w-3"
                                        aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"
                                        /></svg
                                ></a>
                            </div>
                            <div class="mt-2">
                                <Filter
                                    :column="header.column"
                                    :table="table"
                                />
                            </div>
                        </th>
                        <th scope="col" class="w-4 px-6 py-3 text-right">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody
                    v-if="
                        table.getRowModel().rows &&
                        table.getRowModel().rows.length > 0
                    "
                >
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
                                    : 'px-6 py-4'
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
                <!-- Fallback message when data is null or empty -->
                <tbody v-else>
                    <tr>
                        <td colspan="6" class="p-4 text-center">
                            No data available
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
